<?php

namespace Tests\Mocks;

class AtomicSingleSpecialAssetResponse extends AtomicMock
{
    public static function getResponse(): array
    {
        return [
            'success' => true,
            'data' => [
                [
                    'contract' => 'atomicassets',
                    'asset_id' => '1099730973889',
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
                            'mq1.y.c.wam',
                            'neftyblocksd',
                            'blenderizerx',
                            'atomicpacksx',
                            'blend.nefty',
                        ],
                        'notify_accounts' => [],
                        'market_fee' => 0.06,
                        'created_at_block' => '161087638',
                        'created_at_time' => '1641998997500',
                    ],
                    'schema' => [
                        'schema_name' => 'specialbuild',
                        'format' => [
                            [
                                'name' => 'name',
                                'type' => 'string',
                            ],
                            [
                                'name' => 'img',
                                'type' => 'image',
                            ],
                            [
                                'name' => 'Description',
                                'type' => 'string',
                            ],
                            [
                                'name' => 'Type',
                                'type' => 'string',
                            ],
                            [
                                'name' => 'Population',
                                'type' => 'uint64',
                            ],
                            [
                                'name' => 'Season',
                                'type' => 'uint64',
                            ],
                            [
                                'name' => 'Website',
                                'type' => 'string',
                            ],
                        ],
                        'created_at_block' => '162688577',
                        'created_at_time' => '1642799593000',
                    ],
                    'template' => [
                        'template_id' => '442223',
                        'max_supply' => '500',
                        'is_transferable' => true,
                        'is_burnable' => true,
                        'issued_supply' => '356',
                        'immutable_data' => [
                            'img' => 'QmeYJL1PZvNR1bS9GrYBujknRdjGTcgYXVHrpFhwMjzszp',
                            'Type' => 'Unique',
                            'name' => 'Major Plaza',
                            'Season' => '1',
                            'Website' => 'www.DehootValley.io',
                            'Population' => '0',
                            'Description' => 'Full of greatness and glory! Remember the difficult days that the inhabitants of this valley have passed, a reminder of the mistakes made to avoid them in the future, a reminder of the achievements obtained to share them as one people!',
                        ],
                        'created_at_time' => '1645218221000',
                        'created_at_block' => '167523081',
                    ],
                    'mutable_data' => [],
                    'immutable_data' => [],
                    'template_mint' => '356',
                    'backed_tokens' => [],
                    'burned_by_account' => null,
                    'burned_at_block' => null,
                    'burned_at_time' => null,
                    'updated_at_block' => '178898349',
                    'updated_at_time' => '1650906738500',
                    'transferred_at_block' => '178898349',
                    'transferred_at_time' => '1650906738500',
                    'minted_at_block' => '178896804',
                    'minted_at_time' => '1650905966000',
                    'data' => [
                        'img' => 'QmeYJL1PZvNR1bS9GrYBujknRdjGTcgYXVHrpFhwMjzszp',
                        'Type' => 'Unique',
                        'name' => 'Major Plaza',
                        'Season' => '1',
                        'Website' => 'www.DehootValley.io',
                        'Population' => '0',
                        'Description' => 'Full of greatness and glory! Remember the difficult days that the inhabitants of this valley have passed, a reminder of the mistakes made to avoid them in the future, a reminder of the achievements obtained to share them as one people!',
                    ],
                    'name' => 'Major Plaza',
                ],
            ],
            'query_time' => 1651607533179,
        ];
    }
}
