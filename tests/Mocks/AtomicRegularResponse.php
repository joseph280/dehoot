<?php

namespace Tests\Mocks;

use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;

class AtomicRegularResponse extends AtomicMock
{
    public Residential $residential;

    public SpecialBuild $specialBuild;

    public function __construct()
    {
        $data = data_get(self::getResponse(), 'data');

        $population = data_get($data[0], 'data.Population');

        $this->residential = new Residential(
            assetId: data_get($data[0], 'asset_id'),
            templateId: data_get($data[0], 'template.template_id'),
            schema: data_get($data[0], 'schema.schema_name'),
            owner: data_get($data[0], 'owner'),
            imgUrl: data_get($data[0], 'data.img', ''),
            name: data_get($data[0], 'data.name', ''),
            description: data_get($data[0], 'data.Description', ''),
            type: data_get($data[0], 'data.Type'),
            population: $population,
            water: data_get($data[0], 'data.Water', $population),
            energy: data_get($data[0], 'data.Energy', $population),
            level: data_get($data[0], 'data.Level'),
            season: data_get($data[0], 'data.Season'),
        );

        $this->specialBuild = new SpecialBuild(
            assetId: data_get($data[1], 'asset_id'),
            templateId: data_get($data[1], 'template.template_id'),
            schema: data_get($data[1], 'schema.schema_name'),
            owner: data_get($data[1], 'owner'),
            imgUrl: data_get($data[1], 'data.img', ''),
            name: data_get($data[1], 'data.name', ''),
            description: data_get($data[1], 'data.Description', ''),
            type: data_get($data[1], 'data.Type'),
            season: data_get($data[1], 'data.Season')
        );
    }

    public static function getResponse(): array
    {
        return [
            'success' => true,
            'data' => [
                [
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
                        'issued_supply' => '88',
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
