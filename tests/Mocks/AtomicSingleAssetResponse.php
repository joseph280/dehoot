<?php

namespace Tests\Mocks;

class AtomicSingleAssetResponse extends AtomicMock
{
    public static function getResponse(): array
    {
        return [
            'success' => true,
            'data' => [
                0 => [
                    'contract' => 'atomicassets',
                    'asset_id' => '1099668471868',
                    'owner' => 'b1v2m.c.wam',
                    'is_transferable' => true,
                    'is_burnable' => true,
                    'collection' => [
                        'collection_name' => 'dehootvalley',
                        'name' => 'Dehoot Valley',
                        'img' => 'QmPArtsVzBd7acfU3DXQK549JYY3K3SJmqWoqfq2ZuADc1',
                        'author' => 'mq1.y.c.wam',
                        'allow_notify' => true,
                        'authorized_accounts' => [
                            0 => 'mq1.y.c.wam',
                            1 => 'neftyblocksd',
                            2 => 'blenderizerx',
                            3 => 'atomicpacksx',
                            4 => 'blend.nefty',
                        ],
                        'notify_accounts' => [
                        ],
                        'market_fee' => 0.06,
                        'created_at_block' => '161087638',
                        'created_at_time' => '1641998997500',
                    ],
                    'schema' => [
                        'schema_name' => 'residential',
                        'format' => [
                            0 => [
                                'name' => 'name',
                                'type' => 'string',
                            ],
                            1 => [
                                'name' => 'img',
                                'type' => 'image',
                            ],
                            2 => [
                                'name' => 'Description',
                                'type' => 'string',
                            ],
                            3 => [
                                'name' => 'Type',
                                'type' => 'string',
                            ],
                            4 => [
                                'name' => 'Level',
                                'type' => 'uint64',
                            ],
                            5 => [
                                'name' => 'Population',
                                'type' => 'uint64',
                            ],
                            6 => [
                                'name' => 'Season',
                                'type' => 'uint64',
                            ],
                            7 => [
                                'name' => 'Website',
                                'type' => 'string',
                            ],
                        ],
                        'created_at_block' => '161091275',
                        'created_at_time' => '1642000816000',
                    ],
                    'template' => [
                        'template_id' => '431295',
                        'max_supply' => '0',
                        'is_transferable' => true,
                        'is_burnable' => true,
                        'issued_supply' => '95',
                        'immutable_data' => [
                            'img' => 'QmdxLqHD5VESo9iCxo9P3r17ydNQJZAhYHwdWLRQtNHygs',
                            'Type' => 'House',
                            'name' => 'House B1.1',
                            'Level' => '1',
                            'Season' => '1',
                            'Website' => 'www.DehootValley.io',
                            'Population' => '1',
                            'Description' => 'Modern style house based on a square plan with facades of simple lines that are combined with faces of clay bricks',
                        ],
                        'created_at_time' => '1643404496000',
                        'created_at_block' => '163896928',
                    ],
                    'mutable_data' => [
                    ],
                    'immutable_data' => [
                    ],
                    'template_mint' => '60',
                    'backed_tokens' => [
                    ],
                    'burned_by_account' => null,
                    'burned_at_block' => null,
                    'burned_at_time' => null,
                    'updated_at_block' => '171158217',
                    'updated_at_time' => '1647035975500',
                    'transferred_at_block' => '171158217',
                    'transferred_at_time' => '1647035975500',
                    'minted_at_block' => '171158108',
                    'minted_at_time' => '1647035921000',
                    'data' => [
                        'img' => 'QmdxLqHD5VESo9iCxo9P3r17ydNQJZAhYHwdWLRQtNHygs',
                        'Type' => 'House',
                        'name' => 'House B1.1',
                        'Level' => '1',
                        'Season' => '1',
                        'Website' => 'www.DehootValley.io',
                        'Population' => '1',
                        'Description' => 'Modern style house based on a square plan with facades of simple lines that are combined with faces of clay bricks',
                    ],
                    'name' => 'House B1.1',
                ],
            ],
            'query_time' => 1654785089082,
        ];
    }
}
