<?php

namespace Domain\Player\Models;

use Laravel\Sanctum\HasApiTokens;
use Domain\Asset\Models\StakedAsset;
use Domain\Log\Models\TransactionLog;
use Domain\Log\Enums\TransactionStatus;
use Illuminate\Notifications\Notifiable;
use Database\Factories\Player\PlayerFactory;
use Domain\Player\Repositories\PlayerRepository;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Player extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'key_1',
        'key_2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function stakedAssets(): HasMany
    {
        return $this->hasMany(StakedAsset::class);
    }

    public function transactionLogs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }

    protected function isVip(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (! $value) {
                    $value = app(PlayerRepository::class)->getIsVIP($this);
                }

                return $value;
            },
        );
    }

    protected function hasProcessingTransaction(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->transactionLogs()
                ->where('status', TransactionStatus::Processing)
                ->count() > 0
        );
    }

    protected static function newFactory(): PlayerFactory
    {
        return PlayerFactory::new();
    }
}
