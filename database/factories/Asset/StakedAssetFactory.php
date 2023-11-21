<?php

namespace Database\Factories\Asset;

use Domain\Player\Models\Player;
use Domain\Asset\Entities\Service;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\Enums\AssetSchemaType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class StakedAssetFactory extends Factory
{
    protected $model = StakedAsset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assetId = $this->faker->numerify('#############');

        /** @var Player $player */
        $player = Player::factory()->create();

        return [
            'player_id' => $player->id,
            'asset_id' => $assetId,
            'land' => (string) $this->faker->randomDigit,
            'position_x' => $this->faker->numberBetween(1, 12),
            'position_y' => $this->faker->numberBetween(1, 12),
            'data' => new Residential(
                assetId: $assetId,
                templateId: $this->faker->numerify('#############'),
                schema: AssetSchemaType::Residential->value,
                owner: $player->account_id,
                name: $this->faker->word(),
                imgUrl: $this->faker->imageUrl(),
                population: $this->faker->numberBetween(1, 32),
                water: $this->faker->numberBetween(1, 32),
                energy: $this->faker->numberBetween(1, 32),
            ),
            'staked_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'claimed_at' => null,
        ];
    }

    public function withData(array $data): StakedAssetFactory
    {
        $schema = data_get($data, 'schema', AssetSchemaType::Residential);

        return $this->state(
            fn (array $attributes) => [
                'data' => match ($schema) {
                    AssetSchemaType::Residential => $this->residentialBuilding($data, $attributes),
                    AssetSchemaType::Special => $this->specialBuilding($data, $attributes),
                    AssetSchemaType::Service => $this->serviceBuilding($data, $attributes),
                },
            ]
        );
    }

    protected function residentialBuilding(array $data, array $attributes): Residential
    {
        return new Residential(
            assetId: $attributes['asset_id'],
            templateId: data_get($data, 'templateId', $this->faker->numerify('#############')),
            schema: data_get($data, 'schema', AssetSchemaType::Residential)->value,
            owner: data_get($data, 'owner', $attributes['player_id']),
            name: data_get($data, 'name', $this->faker->word()),
            imgUrl: data_get($data, 'imgUrl', $this->faker->imageUrl()),
            population: data_get($data, 'population', $this->faker->numberBetween(1, 32)),
            water: data_get($data, 'water', $this->faker->numberBetween(1, 32)),
            energy: data_get($data, 'energy', $this->faker->numberBetween(1, 32)),
        );
    }

    protected function specialBuilding(array $data, array $attributes): SpecialBuild
    {
        return new SpecialBuild(
            assetId: $attributes['asset_id'],
            templateId: data_get($data, 'templateId', $this->faker->numerify('#############')),
            schema: data_get($data, 'schema', AssetSchemaType::Special)->value,
            owner: data_get($data, 'owner', $attributes['player_id']),
            name: data_get($data, 'name', $this->faker->word()),
            imgUrl: data_get($data, 'imgUrl', $this->faker->imageUrl()),
            population: data_get($data, 'population', $this->faker->numberBetween(1, 32)),
        );
    }

    protected function serviceBuilding(array $data, array $attributes): Service
    {
        return new Service(
            assetId: $attributes['asset_id'],
            templateId: data_get($data, 'templateId', $this->faker->numerify('#############')),
            schema: data_get($data, 'schema', AssetSchemaType::Service)->value,
            owner: data_get($data, 'owner', $attributes['player_id']),
            name: data_get($data, 'name', $this->faker->word()),
            imgUrl: data_get($data, 'imgUrl', $this->faker->imageUrl()),
            capacity: data_get($data, 'capacity', $this->faker->numberBetween(1, 32)),
            type: data_get($data, 'type', $this->faker->randomElement([Service::ENERGY_TYPE, Service::WATER_TYPE])),
        );
    }
}
