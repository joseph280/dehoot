<?php

namespace Tests\Mocks;

class AtomicRegularResponseWithServices extends AtomicMock
{
    public static function getResponse(): array
    {
        return [
            'success' => true,
            'data' => [
                0 => [
                    'contract' => 'atomicassets',
                    'asset_id' => '1099797331267',
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
                        'schema_name' => 'service',
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
                                'name' => 'description',
                                'type' => 'string',
                            ],
                            3 => [
                                'name' => 'type',
                                'type' => 'string',
                            ],
                            4 => [
                                'name' => 'season',
                                'type' => 'uint64',
                            ],
                            5 => [
                                'name' => 'website',
                                'type' => 'string',
                            ],
                            6 => [
                                'name' => 'Capacity',
                                'type' => 'uint64',
                            ],
                        ],
                        'created_at_block' => '178054969',
                        'created_at_time' => '1650484982000',
                    ],
                    'template' => [
                        'template_id' => '535289',
                        'max_supply' => '0',
                        'is_transferable' => true,
                        'is_burnable' => true,
                        'issued_supply' => '3',
                        'immutable_data' => [
                            'img' => 'QmRa4HScgoLwmtDBsGtbJY8QU44pmr5sceSA7c71Rxt7mA',
                            'name' => 'Electric Plant 3',
                            'type' => 'Energy',
                            'season' => '1',
                            'website' => 'www.DehootValley.io',
                            'Capacity' => '350',
                            'description' => 'The electric power plant works by burning natural gas. It has a single electrical generator.',
                        ],
                        'created_at_time' => '1656000350500',
                        'created_at_block' => '189082617',
                    ],
                    'mutable_data' => [
                    ],
                    'immutable_data' => [
                    ],
                    'template_mint' => '2',
                    'backed_tokens' => [
                    ],
                    'burned_by_account' => null,
                    'burned_at_block' => null,
                    'burned_at_time' => null,
                    'updated_at_block' => '189111584',
                    'updated_at_time' => '1656014834000',
                    'transferred_at_block' => '189111584',
                    'transferred_at_time' => '1656014834000',
                    'minted_at_block' => '189111318',
                    'minted_at_time' => '1656014701000',
                    'data' => [
                        'img' => 'QmRa4HScgoLwmtDBsGtbJY8QU44pmr5sceSA7c71Rxt7mA',
                        'name' => 'Electric Plant 3',
                        'type' => 'Energy',
                        'season' => '1',
                        'website' => 'www.DehootValley.io',
                        'Capacity' => '350',
                        'description' => 'The electric power plant works by burning natural gas. It has a single electrical generator.',
                    ],
                    'name' => 'Electric Plant 3',
                ],
                1 => [
                    'contract' => 'atomicassets',
                    'asset_id' => '1099797292502',
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
                        'schema_name' => 'service',
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
                                'name' => 'description',
                                'type' => 'string',
                            ],
                            3 => [
                                'name' => 'type',
                                'type' => 'string',
                            ],
                            4 => [
                                'name' => 'season',
                                'type' => 'uint64',
                            ],
                            5 => [
                                'name' => 'website',
                                'type' => 'string',
                            ],
                            6 => [
                                'name' => 'Capacity',
                                'type' => 'uint64',
                            ],
                        ],
                        'created_at_block' => '178054969',
                        'created_at_time' => '1650484982000',
                    ],
                    'template' => [
                        'template_id' => '535321',
                        'max_supply' => '0',
                        'is_transferable' => true,
                        'is_burnable' => true,
                        'issued_supply' => '3',
                        'immutable_data' => [
                            'img' => 'QmVUvpbjTAnyBrsrTidjbndGtuJqp2jBb1LNdSLujD6MW2',
                            'name' => 'Water Plant 3',
                            'type' => 'Water',
                            'season' => '1',
                            'website' => 'www.DehootValley.io',
                            'Capacity' => '350',
                            'description' => 'Water purification plant with 1 unit capable of producing 350',
                        ],
                        'created_at_time' => '1656002793000',
                        'created_at_block' => '189087502',
                    ],
                    'mutable_data' => [
                    ],
                    'immutable_data' => [
                    ],
                    'template_mint' => '1',
                    'backed_tokens' => [
                    ],
                    'burned_by_account' => null,
                    'burned_at_block' => null,
                    'burned_at_time' => null,
                    'updated_at_block' => '189111584',
                    'updated_at_time' => '1656014834000',
                    'transferred_at_block' => '189111584',
                    'transferred_at_time' => '1656014834000',
                    'minted_at_block' => '189087643',
                    'minted_at_time' => '1656002863500',
                    'data' => [
                        'img' => 'QmVUvpbjTAnyBrsrTidjbndGtuJqp2jBb1LNdSLujD6MW2',
                        'name' => 'Water Plant 3',
                        'type' => 'Water',
                        'season' => '1',
                        'website' => 'www.DehootValley.io',
                        'Capacity' => '350',
                        'description' => 'Water purification plant with 1 unit capable of producing 350',
                    ],
                    'name' => 'Water Plant 3',
                ],
                2 => [
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
                        'issued_supply' => '141',
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
                3 => [
                    'contract' => 'atomicassets',
                    'asset_id' => '1099668471783',
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
                        'template_id' => '431286',
                        'max_supply' => '0',
                        'is_transferable' => true,
                        'is_burnable' => true,
                        'issued_supply' => '278',
                        'immutable_data' => [
                            'img' => 'Qmf58avunRFQJPsh4AZPerdRpFKWdyq8E9KWpA4wH5t22x',
                            'Type' => 'House',
                            'name' => 'Casa Bonita 1',
                            'Level' => '1',
                            'Season' => '1',
                            'Website' => 'www.DehootValley.io',
                            'Population' => '1',
                            'Description' => 'Square-based house with an architecture inspired by constructions made of adobe, where this material is the protagonist along with wood.',
                        ],
                        'created_at_time' => '1643403693000',
                        'created_at_block' => '163895322',
                    ],
                    'mutable_data' => [
                    ],
                    'immutable_data' => [
                    ],
                    'template_mint' => '71',
                    'backed_tokens' => [
                    ],
                    'burned_by_account' => null,
                    'burned_at_block' => null,
                    'burned_at_time' => null,
                    'updated_at_block' => '171158217',
                    'updated_at_time' => '1647035975500',
                    'transferred_at_block' => '171158217',
                    'transferred_at_time' => '1647035975500',
                    'minted_at_block' => '171158096',
                    'minted_at_time' => '1647035915000',
                    'data' => [
                        'img' => 'Qmf58avunRFQJPsh4AZPerdRpFKWdyq8E9KWpA4wH5t22x',
                        'Type' => 'House',
                        'name' => 'Casa Bonita 1',
                        'Level' => '1',
                        'Season' => '1',
                        'Website' => 'www.DehootValley.io',
                        'Population' => '1',
                        'Description' => 'Square-based house with an architecture inspired by constructions made of adobe, where this material is the protagonist along with wood.',
                    ],
                    'name' => 'Casa Bonita 1',
                ],
                4 => [
                    'contract' => 'atomicassets',
                    'asset_id' => '1099668471582',
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
                        'template_id' => '412440',
                        'max_supply' => '0',
                        'is_transferable' => true,
                        'is_burnable' => true,
                        'issued_supply' => '218',
                        'immutable_data' => [
                            'img' => 'QmdQoSbhpV2ZMbJc4DWvU9QbWnrsd5KSmN4zWTv8q9ycAc',
                            'Type' => 'House',
                            'name' => 'House A1.1',
                            'Level' => '1',
                            'Season' => '1',
                            'Website' => 'www.DehootValley.io',
                            'Population' => '1',
                            'Description' => 'Modern-style house is based on a square floor plan with facades of simple lines combined with white and blue glass.',
                        ],
                        'created_at_time' => '1642202254000',
                        'created_at_block' => '161494132',
                    ],
                    'mutable_data' => [
                    ],
                    'immutable_data' => [
                    ],
                    'template_mint' => '69',
                    'backed_tokens' => [
                    ],
                    'burned_by_account' => null,
                    'burned_at_block' => null,
                    'burned_at_time' => null,
                    'updated_at_block' => '171158217',
                    'updated_at_time' => '1647035975500',
                    'transferred_at_block' => '171158217',
                    'transferred_at_time' => '1647035975500',
                    'minted_at_block' => '171158070',
                    'minted_at_time' => '1647035902000',
                    'data' => [
                        'img' => 'QmdQoSbhpV2ZMbJc4DWvU9QbWnrsd5KSmN4zWTv8q9ycAc',
                        'Type' => 'House',
                        'name' => 'House A1.1',
                        'Level' => '1',
                        'Season' => '1',
                        'Website' => 'www.DehootValley.io',
                        'Population' => '1',
                        'Description' => 'Modern-style house is based on a square floor plan with facades of simple lines combined with white and blue glass.',
                    ],
                    'name' => 'House A1.1',
                ],
            ],
            'query_time' => 1656534037052,
        ];
    }
}
