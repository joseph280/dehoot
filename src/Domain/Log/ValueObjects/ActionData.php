<?php

namespace Domain\Log\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ActionData implements Castable
{
    public function __construct(
        public ?string $owner = null,
        public ?string $symbol = null,
        public ?string $issuer = null,
        public ?string $maximum_supply = null,
        public ?string $from = null,
        public ?string $to = null,
        public ?string $quantity = null,
        public ?string $memo = null,
        public ?string $ram_payer = null,
        public ?array $addresses = null,
    ) {
    }

    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes {
            public function get($model, $key, $value, $attributes)
            {
                $data = json_decode($value);

                return new ActionData(
                    owner: data_get($data, 'owner'),
                    symbol: data_get($data, 'symbol'),
                    issuer: data_get($data, 'issuer'),
                    maximum_supply: data_get($data, 'maximum_supply'),
                    from: data_get($data, 'from'),
                    to: data_get($data, 'to'),
                    quantity: data_get($data, 'quantity'),
                    memo: data_get($data, 'memo'),
                    ram_payer: data_get($data, 'ram_payer'),
                    addresses: data_get($data, 'addresses'),
                );
            }

            public function set($model, $key, $value, $attributes)
            {
                $data = [
                    'owner' => data_get($value, 'owner'),
                    'symbol' => data_get($value, 'symbol'),
                    'issuer' => data_get($value, 'issuer'),
                    'maximum_supply' => data_get($value, 'maximum_supply'),
                    'from' => data_get($value, 'from'),
                    'to' => data_get($value, 'to'),
                    'quantity' => data_get($value, 'quantity'),
                    'memo' => data_get($value, 'memo'),
                    'ram_payer' => data_get($value, 'ram_payer'),
                    'addresses' => data_get($value, 'addresses'),
                ];

                return json_encode($data);
            }
        };
    }
}
