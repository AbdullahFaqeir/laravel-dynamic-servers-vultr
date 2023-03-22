<?php

return [
    'providers' => [
        'vultr' => [
            'class' => AbdullahFaqeir\LaravelDynamicServersVultr\Vultr\VultrServerProvider::class,
            'maximum_servers_in_account' => 15,
            'options' => [
                'token' => env('VULTR_TOKEN'),
                'region' => env('VULTR_REGION'),
            ],
        ],
    ],
];
