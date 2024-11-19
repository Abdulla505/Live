<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'file.php' => filter_var(env('file.php', true), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'file.php' => [
        'file.php' => env('file.php', 'file.php'),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application'file.php'Datasources'file.php'default'file.php'host'file.php'localhost'file.php'port'file.php'non_standard_port_number'file.php'username'file.php'my_app'file.php'password'file.php'secret'file.php'database'file.php'my_app'file.php'log'file.php'url'file.php'DATABASE_URL'file.php'EmailTransport'file.php'default'file.php'host'file.php'localhost'file.php'port'file.php'username'file.php'password'file.php'client'file.php'url'file.php'EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
];
