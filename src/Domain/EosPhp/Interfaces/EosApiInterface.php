<?php

namespace Domain\EosPhp\Interfaces;

use Domain\EosPhp\Entities\Code\CachedAbi;
use Domain\EosPhp\Entities\Blockchain\Blockchain;
use Domain\EosPhp\Entities\Transaction\Transaction;
use Domain\EosPhp\Entities\Transaction\TransactionConfig;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

interface EosApiInterface
{
    public function getBalance(string $code, string $account, string $symbol): array;

    public function getPublicKeys(): array;

    public function addPrivateKeys(array $privateKeys): void;

    public function transact(Transaction $transaction, TransactionConfig $transactionConfig): TransactionReceipt;

    public function signTransaction($chainId, $serializeTransaction, $requiredKeys);

    public function getRequiredKeys(Transaction $transaction);

    public function serializeActionsToBin(array $actions);

    public function serializeTransaction(Transaction $transaction);

    public function validateTaposStructure(Transaction $transaction): bool;

    public function generateTapos(Blockchain | null $info = null, Transaction $transaction, int | null $blocksBehind = null, bool | null $useLastIrreversible = null, int $expireSeconds): Transaction;

    public function getTransactionAbis(Transaction $transaction): array;

    public function getCachedAbi(string $accountName): CachedAbi;
}
