<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'back' => 'Previous',
    'finish' => 'Install',
    'next' => 'Next Step',
    'title' => 'UNIT3D Installer',
    'forms' => [
        'errorTitle' => 'The Following errors occurred:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'message' => 'Easy Installation and Setup Wizard.',
        'next'    => 'Check Requirements',
        'templateTitle' => 'Welcome',
        'title'   => 'Laravel Installer',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'next'    => 'Check Permissions',
        'templateTitle' => 'Step 1 | Server Requirements',
        'title' => 'Server Requirements',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'next' => 'Configure Environment',
        'templateTitle' => 'Step 2 | Permissions',
        'title' => 'Permissions',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'classic-button' => 'Classic Text Editor',
            'desc' => 'Please select how you want to configure the apps <code>.env</code> file.',
            'templateTitle' => 'Step 3 | Environment Settings',
            'title' => 'Environment Settings',
            'wizard-button' => 'Form Wizard Setup',
        ],
        'wizard' => [
            'templateTitle' => 'Step 3 | Environment Settings | Guided Wizard',
            'title' => 'Guided <code>.env</code> Wizard',
            'tabs' => [
                'application' => 'Application',
                'database' => 'Database',
                'environment' => 'Environment',
            ],
            'form' => [
                'app_debug_label' => 'App Debug',
                'app_debug_label_false' => 'False',
                'app_debug_label_true' => 'True',
                'app_environment_label' => 'App Environment',
                'app_environment_label_developement' => 'Development',
                'app_environment_label_local' => 'Local',
                'app_environment_label_other' => 'Other',
                'app_environment_label_production' => 'Production',
                'app_environment_label_qa' => 'Qa',
                'app_environment_placeholder_other' => 'Enter your environment...',
                'app_log_level_label' => 'App Log Level',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_critical' => 'critical',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_emergency' => 'emergency',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'notice',
                'app_log_level_label_warning' => 'warning',
                'app_name_label' => 'App Name',
                'app_name_placeholder' => 'App Name',
                'app_url_label' => 'App Url',
                'app_url_placeholder' => 'App Url',
                'db_connection_label' => 'Database Connection',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Database Host',
                'db_host_placeholder' => 'Database Host',
                'db_name_label' => 'Database Name',
                'db_name_placeholder' => 'Database Name',
                'db_password_label' => 'Database Password',
                'db_password_placeholder' => 'Database Password',
                'db_port_label' => 'Database Port',
                'db_port_placeholder' => 'Database Port',
                'db_username_label' => 'Database User Name',
                'db_username_placeholder' => 'Database User Name',
                'name_required' => 'An environment name is required.',

                'app_tabs' => [
                    'broadcasting_label' => 'Broadcast Driver',
                    'broadcasting_placeholder' => 'Broadcast Driver',
                    'broadcasting_title' => 'Broadcasting, Caching, Session, &amp; Queue',
                    'cache_label' => 'Cache Driver',
                    'cache_placeholder' => 'Cache Driver',
                    'more_info' => 'More Info',
                    'queue_label' => 'Queue Driver',
                    'queue_placeholder' => 'Queue Driver',
                    'redis_host' => 'Redis Host',
                    'redis_label' => 'Redis Driver',
                    'redis_password' => 'Redis Password',
                    'redis_port' => 'Redis Port',
                    'session_label' => 'Session Driver',
                    'session_placeholder' => 'Session Driver',

                    'mail_driver_label' => 'Mail Driver',
                    'mail_driver_placeholder' => 'Mail Driver',
                    'mail_encryption_label' => 'Mail Encryption',
                    'mail_encryption_placeholder' => 'Mail Encryption',
                    'mail_host_label' => 'Mail Host',
                    'mail_host_placeholder' => 'Mail Host',
                    'mail_label' => 'Mail',
                    'mail_password_label' => 'Mail Password',
                    'mail_password_placeholder' => 'Mail Password',
                    'mail_port_label' => 'Mail Port',
                    'mail_port_placeholder' => 'Mail Port',
                    'mail_username_label' => 'Mail Username',
                    'mail_username_placeholder' => 'Mail Username',

                    'pusher_app_id_label' => 'Pusher App Id',
                    'pusher_app_id_palceholder' => 'Pusher App Id',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                    'pusher_label' => 'Pusher',
                ],
                'buttons' => [
                    'install' => 'Install',
                    'setup_application' => 'Setup Application',
                    'setup_database' => 'Setup Database',
                ],
            ],
        ],
        'classic' => [
            'back' => 'Use Form Wizard',
            'install' => 'Save and Install',
            'save' => 'Save .env',
            'templateTitle' => 'Step 3 | Environment Settings | Classic Editor',
            'title' => 'Classic Environment Editor',
        ],
        'errors' => 'Unable to save the .env file, Please create it manually.',
        'success' => 'Your .env file settings have been saved.',
    ],

    'install' => 'Install',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel Installer successfully INSTALLED on ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'console' => 'Application Console Output:',
        'env' => 'Final .env File:',
        'exit' => 'Click here to exit',
        'finished' => 'Application has been successfully installed.',
        'log' => 'Installation Log Entry:',
        'migration' => 'Migration &amp; Seed Console Output:',
        'templateTitle' => 'Installation Finished',
        'title' => 'Installation Finished',
    ],

    /**
     *
     * Update specific translations
     *
     */
    'updater' => [
        /**
         *
         * Shared translations.
         *
         */
        'title' => 'Laravel Updater',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'message' => 'Welcome to the update wizard.',
            'title'   => 'Welcome To The Updater',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'install_updates' => "Install Updates",
            'message' => 'There is 1 update.|There are :number updates.',
            'title'   => 'Overview',
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'exit' => 'Click here to exit',
            'finished' => 'Application\'s database has been successfully updated.',
            'title' => 'Finished',
        ],

        'log' => [
            'success_message' => 'Laravel Installer successfully UPDATED on ',
        ],
    ],
];
