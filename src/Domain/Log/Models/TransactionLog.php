<?php

namespace Domain\Log\Models;

use Domain\Player\Models\Player;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionLog extends BaseModel
{
    protected $fillable = [
        'player_id',
        'wallet_id',
        'amount',
        'transaction_id',
        'status',
        'type',
        'asset_ids',
    ];

    protected $casts = [
        'asset_ids' => 'array',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
