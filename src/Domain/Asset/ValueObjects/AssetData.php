<?php

namespace Domain\Asset\ValueObjects;

use Domain\Asset\Entities\Asset;
use Domain\Asset\Entities\Service;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\Enums\AssetSchemaType;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AssetData implements Castable
{
    public const POPULATION_DEFAULT = 1;

    public function __construct(
        public string $templateId,
        public string $schema,
        public ?int $population = null,
        public ?int $water = null,
        public ?int $energy = null,
    ) {
    }

    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class implements CastsAttributes {
            public function get($model, $key, $value, $attributes): Asset
            {
                $data = json_decode($value, true);
                $schema = data_get($data, 'schema');

                $assetId = data_get($data, 'assetId');

                if (! $assetId) {
                    $data = $this->generateMissingData($data, $model);
                }

                $data['position_x'] = data_get($attributes, 'position_x');
                $data['position_y'] = data_get($attributes, 'position_y');

                return match ($schema) {
                    AssetSchemaType::Special->value => SpecialBuild::fromArray($data),
                    AssetSchemaType::Service->value => Service::fromArray($data),
                    default => Residential::fromArray($data),
                };
            }

            public function set($model, $key, $value, $attributes): string
            {
                return is_array($value) ? json_encode($value) : json_encode($value->toArray());
            }

            /**
             * This function allows us to maintain compatibility with
             * the old StakedAsset data column structure that consisted of:
             * template_id, schema, water, energy and population.
             *
             * In order to satisfy the Asset entity constructor params
             * this function generates asset_id, template_id and owner (id)
             * into a provided $data array.
             *
             * @param mixed $model
             * @param array $data
             *
             * @return array $data
             */
            protected function generateMissingData(array $data, mixed $model)
            {
                $data['templateId'] = data_get($data, 'template_id');
                $data['assetId'] = $model->asset_id;
                $data['owner'] = $model->player->account_id;

                return $data;
            }
        };
    }
}
