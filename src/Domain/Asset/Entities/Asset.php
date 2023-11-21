<?php

namespace Domain\Asset\Entities;

use Domain\Shared\ValueObjects\Token;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\ValueObjects\Dimension;
use Domain\Asset\Effects\MajorPlazaEffect;
use Illuminate\Contracts\Support\Arrayable;

abstract class Asset implements Arrayable
{
    public function __construct(
        public string $assetId,
        public string $templateId,
        public string $schema,
        public string $owner,
        public ?string $name = '',
        public ?string $imgUrl = '',
        public ?bool $staking = null,
        public ?int $capacity = null,
        public?int $position_x = null,
        public?int $position_y = null,
        public ?string $description = null,
        public ?string $type = null,
        public ?string $population = null,
        public ?int $water = null,
        public ?int $energy = null,
        public ?string $level = null,
        public ?string $season = null,
        public ?Token $stakedBalance = null,
        public ?int $rows = null,
        public ?int $columns = null,
    ) {
        $this->stakedBalance = $stakedBalance ?? new Token(0);
    }

    public static function fromArray(array $data): self
    {
        return new static(
            assetId: data_get($data, 'assetId'),
            templateId: data_get($data, 'templateId'),
            schema: data_get($data, 'schema'),
            owner: data_get($data, 'owner'),
            staking: data_get($data, 'staking'),
            name: data_get($data, 'name', ''),
            imgUrl: data_get($data, 'imgUrl', ''),
            capacity: (int) data_get($data, 'capacity'),
            description: data_get($data, 'description', ''),
            type: data_get($data, 'type'),
            population: (int) data_get($data, 'population'),
            water: (int) data_get($data, 'water', data_get($data, 'population')),
            energy: (int) data_get($data, 'energy', data_get($data, 'population')),
            level: data_get($data, 'level'),
            season: data_get($data, 'season'),
            position_x: data_get($data, 'position_x'),
            position_y: data_get($data, 'position_y'),
            rows: (int) data_get($data, 'rows', 1),
            columns: (int) data_get($data, 'columns', 1)
        );
    }

    public function hasSchemaType(AssetSchemaType $schemaType): bool
    {
        return $this->schema === $schemaType->value;
    }

    public function hasTemplateId(string $templateId): bool
    {
        return $this->templateId === $templateId;
    }

    public function toArray(): array
    {
        return [
            'assetId' => $this->assetId,
            'templateId' => $this->templateId,
            'schema' => $this->schema,
            'owner' => $this->owner,
            'imgUrl' => $this->imgUrl,
            'name' => $this->name,
            'staking' => $this->staking,
            'capacity' => $this->capacity,
            'description' => $this->description,
            'type' => $this->type,
            'population' => $this->population,
            'water' => $this->water,
            'energy' => $this->energy,
            'level' => $this->level,
            'season' => $this->season,
            'stakedBalance' => $this->stakedBalance,
            'position_x' => $this->position_x,
            'position_y' => $this->position_y,
            'rows' => $this->rows,
            'columns' => $this->columns,
        ];
    }

    public function getDimensions($templateId): Dimension
    {
        return match ($templateId) {
            MajorPlazaEffect::TEMPLATE_ID => Dimension::from(2, 2),
            default => Dimension::from(1, 1)
        };
    }

    public function effect(): mixed
    {
        return null;
    }
}
