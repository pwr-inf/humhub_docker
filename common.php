<?php

return [
    // ...
    'components' => [

        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],

        // ...

        'authClientCollection' => [
            'clients' => [
                // ...
                'engine' => [
                    'class' => 'humhub\modules\user\authclient\Engine',
#                    'clientId' => 'humhub',
#                    'clientSecret' => 'humhub1',
                ],
            ],
        ],
        // ...
    ],
    // ...
];
