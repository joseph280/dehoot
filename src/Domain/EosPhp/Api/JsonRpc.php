<?php

namespace Domain\EosPhp\Api;

use Exception;
use GuzzleHttp\Client;
use Domain\Shared\Objects\Printer;
use Domain\EosPhp\Entities\Block\Block;
use Domain\EosPhp\Entities\Code\CachedAbi;
use Domain\EosPhp\Entities\Account\Account;
use Domain\EosPhp\Entities\Block\BlockHeader;
use GuzzleHttp\Exception\BadResponseException;
use Domain\EosPhp\Entities\Blockchain\Blockchain;
use Domain\EosPhp\Entities\Request\RefundRequest;
use Domain\EosPhp\Entities\Resource\ResourceLimits;
use Domain\EosPhp\Entities\Transaction\Transaction;
use Domain\EosPhp\Entities\Resource\ResourceOverview;
use Domain\EosPhp\Entities\Resource\ResourceDelegation;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class JsonRpc
{
    protected $client;

    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
        ]);

        $this->baseUrl = $baseUrl;
    }

    public function makeRequest(string $endpoint, mixed $body = null, string $method = 'POST')
    {
        try {
            return $this->client->request($method, $endpoint, [
                'body' => $body ? json_encode($body) : null,
            ]);
        } catch (BadResponseException $e) {
            $exceptionBody = json_decode($e->getResponse()->getBody());

            $args = [
                'EOSIO HTTP Code' => data_get($exceptionBody, 'code'),
                'EOSIO Code' => data_get($exceptionBody, 'error.code'),
                'EOSIO Name' => data_get($exceptionBody, 'error.name'),
                'EOSIO Summary' => data_get($exceptionBody, 'error.what'),
                'EOSIO Details' => json_encode(data_get($exceptionBody, 'error.details')),
                'RPC' => $this->baseUrl,
                'HTTP Method' => $method,
                'HTTP Status Code' => $e->getCode(),
                'Error Message' => $e->getMessage(),
                'Body' => json_encode($body),
            ];

            throw new Exception(Printer::makeExceptionMessage($args));
        }
    }

    public function getCurrencyBalance(string $code, string $account, string $symbol): array
    {
        $response = $this->makeRequest('/v1/chain/get_currency_balance', [
            'code' => $code,
            'account' => $account,
            'symbol' => $symbol,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getInfo(): Blockchain
    {
        $response = $this->makeRequest('/v1/chain/get_info');

        $response = json_decode($response->getBody(), true);

        return new Blockchain(
            server_version: data_get($response, 'server_version'),
            chain_id: data_get($response, 'chain_id'),
            head_block_num: data_get($response, 'head_block_num'),
            last_irreversible_block_num: data_get($response, 'last_irreversible_block_num'),
            last_irreversible_block_id: data_get($response, 'last_irreversible_block_id'),
            head_block_id: data_get($response, 'head_block_id'),
            head_block_time: data_get($response, 'head_block_time'),
            head_block_producer: data_get($response, 'head_block_producer'),
            virtual_block_cpu_limit: data_get($response, 'virtual_block_cpu_limit'),
            virtual_block_net_limit: data_get($response, 'virtual_block_net_limit'),
            block_cpu_limit: data_get($response, 'block_cpu_limit'),
            block_net_limit: data_get($response, 'block_net_limit'),
        );
    }

    public function getAccount(string $accountName): Account
    {
        $response = $this->makeRequest('/v1/chain/get_account', ['account_name' => $accountName]);

        $result = json_decode($response->getBody(), true);

        $net_limit = data_get($response, 'net_limit');
        $cpu_limit = data_get($response, 'cpu_limit');
        $total_resources = data_get($response, 'total_resources');
        $self_delegated_bandwidth = data_get($response, 'self_delegated_bandwidth');
        $refund_request = data_get($response, 'refund_request');
        $subjective_cpu_bill_limit = data_get($response, 'subjective_cpu_bill_limit');

        return new Account(
            account_name: data_get($response, 'account_name'),
            head_block_num: data_get($response, 'head_block_num'),
            head_block_time: data_get($response, 'head_block_time'),
            privileged: data_get($response, 'privileged'),
            last_code_update: data_get($response, 'last_code_update'),
            created: data_get($response, 'created'),
            core_liquid_balance: data_get($response, 'core_liquid_balance'),
            ram_quota: data_get($response, 'ram_quota'),
            net_weight: data_get($response, 'net_weight'),
            cpu_weight: data_get($response, 'cpu_weight'),
            net_limit: new ResourceLimits(
                max: data_get($net_limit, 'max'),
                available: data_get($net_limit, 'available'),
                used: data_get($net_limit, 'used'),
            ),
            cpu_limit: new ResourceLimits(
                max: data_get($cpu_limit, 'max'),
                available: data_get($cpu_limit, 'available'),
                used: data_get($cpu_limit, 'used'),
            ),
            ram_usage: data_get($response, 'ram_usage'),
            permissions: data_get($response, 'permissions'),
            total_resources: new ResourceOverview(
                owner: data_get($total_resources, 'owner'),
                ram_bytes: data_get($total_resources, 'ram_bytes'),
                net_weight: data_get($total_resources, 'net_weight'),
                cpu_weight: data_get($total_resources, 'cpu_weight'),
            ),
            self_delegated_bandwidth: new ResourceDelegation(
                from: data_get($self_delegated_bandwidth, 'from'),
                to: data_get($self_delegated_bandwidth, 'to'),
                net_weight: data_get($self_delegated_bandwidth, 'net_weight'),
                cpu_weight: data_get($self_delegated_bandwidth, 'cpu_weight'),
            ),
            refund_request: new RefundRequest(
                owner: data_get($refund_request, 'owner'),
                request_time: data_get($refund_request, 'request_time'),
                net_amount: data_get($refund_request, 'net_amount'),
                cpu_amount: data_get($refund_request, 'cpu_amount'),
            ),
            subjective_cpu_bill_limit: new ResourceLimits(
                max: data_get($subjective_cpu_bill_limit, 'max'),
                available: data_get($subjective_cpu_bill_limit, 'available'),
                used: data_get($subjective_cpu_bill_limit, 'used'),
            ),
            voter_info: data_get($response, 'voter_info'),
            rex_info: data_get($response, 'rex_info'),
        );
    }

    public function getBlockInfo(int $blockNum): Block
    {
        $response = $this->makeRequest('/v1/chain/get_block_info', [
            'block_num' => $blockNum,
        ]);

        $response = json_decode($response->getBody(), true);

        return new Block(
            timestamp: data_get($response, 'timestamp'),
            producer: data_get($response, 'producer'),
            confirmed: data_get($response, 'confirmed'),
            previous: data_get($response, 'previous'),
            transaction_mroot: data_get($response, 'transaction_mroot'),
            action_mroot: data_get($response, 'action_mroot'),
            schedule_version: data_get($response, 'schedule_version'),
            new_producers: data_get($response, 'new_producers'),
            header_extensions: data_get($response, 'header_extensions'),
            new_protocol_features: data_get($response, 'new_protocol_features'),
            producer_signature: data_get($response, 'producer_signature'),
            transactions: data_get($response, 'transactions'),
            block_extensions: data_get($response, 'block_extensions'),
            id: data_get($response, 'id'),
            block_num: data_get($response, 'block_num'),
            ref_block_num: data_get($response, 'ref_block_num'),
            ref_block_prefix: data_get($response, 'ref_block_prefix'),
        );
    }

    public function getBlock(int | string $blockNumOrId): Block
    {
        $response = $this->makeRequest('/v1/chain/get_block', [
            'block_num_or_id' => $blockNumOrId,
        ]);

        $response = json_decode($response->getBody(), true);

        return new Block(
            timestamp: data_get($response, 'timestamp'),
            producer: data_get($response, 'producer'),
            confirmed: data_get($response, 'confirmed'),
            previous: data_get($response, 'previous'),
            transaction_mroot: data_get($response, 'transaction_mroot'),
            action_mroot: data_get($response, 'action_mroot'),
            schedule_version: data_get($response, 'schedule_version'),
            new_producers: data_get($response, 'new_producers'),
            header_extensions: data_get($response, 'header_extensions'),
            new_protocol_features: data_get($response, 'new_protocol_features'),
            producer_signature: data_get($response, 'producer_signature'),
            transactions: data_get($response, 'transactions'),
            block_extensions: data_get($response, 'block_extensions'),
            id: data_get($response, 'id'),
            block_num: data_get($response, 'block_num'),
            ref_block_num: data_get($response, 'ref_block_num'),
            ref_block_prefix: data_get($response, 'ref_block_prefix'),
        );
    }

    public function getBlockHeaderState(int | string $blockNumOrId): BlockHeader
    {
        $response = $this->makeRequest('/v1/chain/get_block_header_state', [
            'block_num_or_id' => $blockNumOrId,
        ]);

        $response = json_decode($response->getBody(), true);

        $header = data_get($response, 'header');

        return new BlockHeader(
            id: data_get($response, 'id'),
            block_num: data_get($response, 'block_num'),
            header: new Block(
                timestamp: data_get($header, 'timestamp'),
                producer: data_get($header, 'producer'),
                confirmed: data_get($header, 'confirmed'),
                previous: data_get($header, 'previous'),
                transaction_mroot: data_get($header, 'transaction_mroot'),
                action_mroot: data_get($header, 'action_mroot'),
                schedule_version: data_get($header, 'schedule_version'),
                new_producers: data_get($header, 'new_producers'),
                header_extensions: data_get($header, 'header_extensions'),
                new_protocol_features: data_get($header, 'new_protocol_features'),
                producer_signature: data_get($header, 'producer_signature'),
                transactions: data_get($header, 'transactions'),
                block_extensions: data_get($header, 'block_extensions'),
                id: data_get($header, 'id'),
                block_num: data_get($header, 'block_num'),
                ref_block_num: data_get($header, 'ref_block_num'),
                ref_block_prefix: data_get($header, 'ref_block_prefix'),
            ),
            dpos_proposed_irreversible_blocknum: data_get($response, 'dpos_proposed_irreversible_blocknum'),
            dpos_irreversible_blocknum: data_get($response, 'dpos_irreversible_blocknum'),
            pending_schedule: data_get($response, 'pending_schedule'),
            active_schedule: data_get($response, 'active_schedule'),
            blockroot_merkle: data_get($response, 'blockroot_merkle'),
            producer_to_last_produced: data_get($response, 'producer_to_last_produced'),
            producer_to_last_implied_irb: data_get($response, 'producer_to_last_implied_irb'),
            confirm_count: data_get($response, 'confirm_count'),
            confirmations: data_get($response, 'confirmations'),
            block_signing_key: data_get($response, 'block_signing_key'),
            pending_schedule_hash: data_get($response, 'pending_schedule_hash'),
            bft_irreversible_blocknum: data_get($response, 'bft_irreversible_blocknum'),
            pending_schedule_lib_num: data_get($response, 'pending_schedule_lib_num'),
            valid_block_signing_authority: data_get($response, 'valid_block_signing_authority'),
            activated_protocol_features: data_get($response, 'activated_protocol_features'),
            additional_signatures: data_get($response, 'additional_signatures'),
        );
    }

    public function getRawAbi(string $accountName): CachedAbi
    {
        $response = $this->makeRequest('/v1/chain/get_raw_abi', [
            'account_name' => $accountName,
        ]);

        $response = json_decode($response->getBody(), true);

        return new CachedAbi(
            account_name: data_get($response, 'account_name'),
            code_hash: data_get($response, 'code_hash'),
            abi_hash: data_get($response, 'abi_hash'),
            abi: data_get($response, 'abi'),
            decoded_abi: data_get($response, 'decoded_abi'),
        );
    }

    public function abiJsonToBin(string $code, string $action, array $args): string
    {
        $response = $this->makeRequest('/v1/chain/abi_json_to_bin', [
            'code' => $code,
            'action' => $action,
            'args' => $args,
        ]);

        return json_decode($response->getBody(), true)['binargs'];
    }

    public function getRequiredKeys(Transaction $transaction, array $availableKeys): array
    {
        $response = $this->makeRequest('/v1/chain/get_required_keys', [
            'transaction' => $transaction,
            'available_keys' => $availableKeys,
        ]);

        return json_decode($response->getBody(), true)['required_keys'];
    }

    public function pushTransaction(array $signatures, bool $compression, string | array $packedContextFreeData, string $serializedTransaction): TransactionReceipt
    {
        $response = $this->makeRequest('/v1/chain/push_transaction', [
            'signatures' => $signatures,
            'compression' => $compression,
            'packed_context_free_data' => $packedContextFreeData,
            'packed_trx' => $serializedTransaction,
        ]);

        $response = json_decode($response->getBody(), true);

        return new TransactionReceipt(
            transaction_id: data_get($response, 'transaction_id'),
            processed: data_get($response, 'processed'),
        );
    }
}
