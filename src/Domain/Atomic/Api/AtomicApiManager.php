<?php

namespace Domain\Atomic\Api;

use Domain\Player\Models\Player;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class AtomicApiManager implements AtomicApiManagerInterface
{
    public function __construct(
        protected string $baseUrl,
        protected string $collectionName,
        protected string $schemaNames,
    ) {
    }

    public function asset(Player $player, string $templateId): array | null
    {
        return $this->handleRequest(
            sprintf('%s/atomicassets/v1/assets', $this->baseUrl),
            [
                'collection_name' => $this->collectionName,
                'schema_name' => $this->schemaNames,
                'template_id' => $templateId,
                'owner' => $player->account_id,
                'limit' => 1,
            ]
        )->json();
    }

    public function assets(Player $player): array | null
    {
        return $this->handleRequest(
            sprintf('%s/atomicassets/v1/assets', $this->baseUrl),
            [
                'collection_name' => $this->collectionName,
                'schema_name' => $this->schemaNames,
                'owner' => $player->account_id,
                'limit' => 1000,
            ]
        )->json();
    }

    public function specialAssets(Player $player): array | null
    {
        return $this->handleRequest(
            sprintf('%s/atomicassets/v1/assets', $this->baseUrl),
            [
                'collection_name' => $this->collectionName,
                'schema_name' => 'specialbuild',
                'owner' => $player->account_id,
                'limit' => 100,
            ]
        )->json();
    }

    private function handleRequest(string $url, array $args): Response
    {
        return Http::get($url, $args)->throw();
    }
}
