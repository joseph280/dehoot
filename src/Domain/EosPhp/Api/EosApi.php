<?php

namespace Domain\EosPhp\Api;

use Exception;
use Throwable;
use Domain\EosPhp\Signature\Ecdsa;
use Domain\EosPhp\Shared\Serialize;
use Domain\EosPhp\Entities\Code\BinaryAbi;
use Domain\EosPhp\Entities\Code\CachedAbi;
use Domain\EosPhp\Interfaces\EosApiInterface;
use Domain\EosPhp\Entities\Block\BlockTaposInfo;
use Domain\EosPhp\Entities\Blockchain\Blockchain;
use Domain\EosPhp\Entities\Transaction\Transaction;
use Domain\EosPhp\Entities\Transaction\TransactionConfig;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class EosApi implements EosApiInterface
{
    /** Issues RPC calls */
    public JsonRpc $rpc;

    /** Identifies chain */
    public null | string $chainId;

    /** Wallet import format private keys */
    protected $privateKeys = [];

    public CachedAbi $cachedAbi;

    public function __construct(JsonRpc $rpc, array $privKeys = [], string $chainId = null)
    {
        $this->rpc = $rpc;
        $this->chainId = $chainId;

        if ($privKeys) {
            $this->addPrivateKeys($privKeys);
        }
    }

    public function getBalance(string $code, string $account, string $symbol): array
    {
        return $this->rpc->getCurrencyBalance(code: $code, account: $account, symbol: $symbol);
    }

    public function getPublicKeys(): array
    {
        return array_keys($this->privateKeys);
    }

    public function addPrivateKeys(array $privateKeys): void
    {
        $newKeys = [];

        foreach ($privateKeys as $key) {
            try {
                $newKeys[Ecdsa::privateToPublic($key)] = $key;
            } catch (Exception $e) {
                throw new Exception("Key: ${key} is not a valid WIF key");
            }
        }
        $this->privateKeys = array_unique(array_merge($this->privateKeys, $newKeys));
    }

    public function transact(Transaction $transaction, TransactionConfig $transactionConfig): TransactionReceipt
    {
        $information = '';
        $default = date_default_timezone_get();

        if (is_numeric($transactionConfig->blocksBehind) && $transactionConfig->useLastIrreversible) {
            throw new Exception('Use either blocksBehind or useLastIrreversible');
        }

        if (! $this->chainId) {
            $information = $this->rpc->getInfo();
            $this->chainId = $information->chain_id;
        }

        if ((is_numeric($transactionConfig->blocksBehind) || $transactionConfig->useLastIrreversible) && $transactionConfig->expireSeconds) {
            $transaction = $this->generateTapos($information, $transaction, $transactionConfig->blocksBehind, $transactionConfig->useLastIrreversible, $transactionConfig->expireSeconds);
        }

        if (! $this->validateTaposStructure($transaction)) {
            throw new Exception('Required configuration or TAPOS fields are not present');
        }

        $transaction->transaction_extensions = [];
        $transaction->actions = $this->serializeActionsToBin($transaction->actions);
        $serializedTransaction = $this->serializeTransaction($transaction);
        $requiredKeys = $this->getRequiredKeys($transaction);
        date_default_timezone_set($default);
        $signatures = $this->signTransaction($this->chainId, $serializedTransaction, $requiredKeys);

        return $this->rpc->pushTransaction(
            signatures: $signatures,
            compression: 0,
            packedContextFreeData: '',
            serializedTransaction: $serializedTransaction,
        );
    }

    public function signTransaction($chainId, $serializeTransaction, $requiredKeys)
    {
        $packedContextFreeData = '0000000000000000000000000000000000000000000000000000000000000000';
        $signBuf = $chainId . $serializeTransaction . $packedContextFreeData;

        $signatures = [];

        foreach ($requiredKeys as $key => $value) {
            $signatures[] = Ecdsa::sign($signBuf, $this->privateKeys[$value]);
        }

        return $signatures;
    }

    public function getRequiredKeys(Transaction $transaction)
    {
        if (isset($transaction->expiration)) {
            $transaction->expiration = date('Y-m-d\TH:i:s.000', $transaction->expiration);
        }

        return $this->rpc->getRequiredKeys(
            transaction: $transaction,
            availableKeys: array_keys($this->privateKeys)
        );
    }

    public function serializeActionsToBin(array $actions)
    {
        $actionsAsBin = [];

        foreach ($actions as $action) {
            $action->data = $this->rpc->abiJsonToBin(
                code: $action->account,
                action: $action->name,
                args: $action->data
            );
            array_push($actionsAsBin, $action);
        }

        return $actionsAsBin;
    }

    public function serializeTransaction(Transaction $transaction)
    {
        return Serialize::transaction($transaction);
    }

    public function validateTaposStructure(Transaction $transaction): bool
    {
        return (
            $transaction->expiration &&
            is_numeric($transaction->ref_block_num) &&
            is_numeric($transaction->ref_block_prefix)
        );
    }

    public function generateTapos(Blockchain | null $info = null, Transaction $transaction, int | null $blocksBehind = null, bool | null $useLastIrreversible = null, int $expireSeconds): Transaction
    {
        if (! $info) {
            $info = $this->rpc->getInfo();
        }

        if ($useLastIrreversible) {
            $block = $this->tryRefBlockFromGetInfo($info);
            $header = Serialize::transactionHeader($block, $info, $expireSeconds);

            $transaction->expiration = $header->expiration;
            $transaction->ref_block_num = $header->ref_block_num;
            $transaction->ref_block_prefix = $header->ref_block_prefix;

            return $transaction;
        }

        $taposBlockNumber = $info->head_block_num - $blocksBehind;

        $refBlock =
            $taposBlockNumber <= $info->last_irreversible_block_num
                ? $this->tryGetBlockInfo($taposBlockNumber)
                : $this->tryGetBlockHeaderState($taposBlockNumber);

        $header = Serialize::transactionHeader($refBlock, $info, $expireSeconds);

        $transaction->expiration = $header->expiration;
        $transaction->ref_block_num = $header->ref_block_num;
        $transaction->ref_block_prefix = $header->ref_block_prefix;

        return $transaction;
    }

    public function getTransactionAbis(Transaction $transaction): array
    {
        $actions = [];

        if ($transaction->context_free_actions) {
            array_push($actions, $transaction->context_free_actions);
        }

        array_push($actions, $transaction->actions);

        $accounts = array_map(fn ($action) => $action[0]->account, $actions);

        $binaryAbi = array_map(function ($account) {
            return new BinaryAbi(
                accountName: $account,
                abi: $this->getCachedAbi($account)->abi,
            );
        }, $accounts);

        return $binaryAbi;
    }

    public function getCachedAbi(string $accountName): CachedAbi
    {
        $cachedAbi = new CachedAbi();

        try {
            $cachedAbi = $this->rpc->getRawAbi($accountName);
            $cachedAbi->decoded_abi = base64_decode($cachedAbi->abi);
        } catch (Throwable $th) {
            throw $th;
        }

        if (! $cachedAbi) {
            throw new Exception('Missing abi for' . $accountName);
        }

        $this->cachedAbi = $cachedAbi;

        return $cachedAbi;
    }

    private function tryGetBlockInfo(int $blockNumber)
    {
        try {
            return $this->rpc->getBlockInfo($blockNumber);
        } catch (Exception $ex) {
            return $this->rpc->getBlock($blockNumber);
        }
    }

    private function tryGetBlockHeaderState(int $taposBlockNumber)
    {
        try {
            return $this->rpc->getBlockHeaderState($taposBlockNumber);
        } catch (Exception $ex) {
            return $this->tryGetBlockInfo($taposBlockNumber);
        }
    }

    private function tryRefBlockFromGetInfo(Blockchain $info): BlockTaposInfo
    {
        if (
            property_exists($info, 'last_irreversible_block_id') &&
            property_exists($info, 'last_irreversible_block_num') &&
            property_exists($info, 'last_irreversible_block_time')
        ) {
            return new BlockTaposInfo(
                block_num: $info->last_irreversible_block_num,
                id: $info->last_irreversible_block_id,
                timestamp: $info->last_irreversible_block_time,
                header: null
            );
        }

        $block = $this->tryGetBlockInfo($info->last_irreversible_block_num);

        return new BlockTaposInfo(
            block_num: $block->block_num,
            id: $block->id,
            timestamp: $block->timestamp,
            header: null
        );
    }
}
