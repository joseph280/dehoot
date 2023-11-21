<?php

return [
    'stake' => [
        'success' => 'The asset has been staked successfully.',
        'reached_stake_limit' => 'You reached the limit of assets in staking.',
        'reached_consumption_limit' => 'More service capacity is required to stake this building.',
    ],
    'unstake' => [
        'success' => 'The asset has been unstaked successfully.',
        'cant_be_under_consumption_limit' => 'This cant be unstaked because your city will be under consumption limit.',
        'limit' => [
            'major_plaza' => 'You must have a maximum of 10 building assets in staking to withdraw the Major Plaza.',
        ],
    ],
    'errors' => [
        'asset_already_in_staking' => 'The requested asset is already in staking.',
        'asset_doesnt_exist' => 'The requested asset is not in your land.',
    ],
    'claim_all' => [
        'success' => 'The city reward has been claimed successfully.',
    ],
    'claim' => [
        'processing' => 'Your reward is being processed.',
    ],
    'transaction' => [
        'processing' => 'Your transaction is being processed.',
        'already_processing' => 'You have an active processing transaction, please try again later.',
    ],
];
