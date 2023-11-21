<?php

namespace Database\Factories\Log;

use Domain\Player\Models\Player;
use Domain\Log\Enums\TransactionType;
use Domain\Log\Models\TransactionLog;
use Domain\Log\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransactionLog>
 */
class TransactionLogFactory extends Factory
{
    protected $model = TransactionLog::class;

    public function definition(): array
    {
        return [
            'player_id' => fn () => Player::factory()->create()->id,
            'wallet_id' => fn (array $attributes) => Player::find($attributes['player_id'])->account_id,
            'transaction_id' => $this->faker->numerify('#############'),
            'status' => TransactionStatus::Success->value,
            'asset_ids' => [],
            'type' => TransactionType::ClaimAll,
        ];
    }

    public function success(): TransactionLogFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TransactionStatus::Success->value,
            ];
        });
    }

    public function failed(): TransactionLogFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TransactionStatus::Failed->value,
            ];
        });
    }

    public function processing(): TransactionLogFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TransactionStatus::Processing->value,
            ];
        });
    }
}
