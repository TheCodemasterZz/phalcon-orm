<?php

/**
 * Module-level configuration file, used in conjunction with Phalcon Plus if
 * available.
 */

return [
    'phalcon-orm' => [
        'connection' => [
            'driver' => 'mysql',
        ],
        'metadata' => [
            'driver' => 'memory',
        ],
    ],
    'phalcon-cli' => [
        'tasks' => [
            'orm:generate' => [
                'class' => 'LukeZbihlyj\PhalconOrm\Cli\CliController',
                'description' => 'Generate the schema for the active database.',
            ],
        ],
    ],
];
