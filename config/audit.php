<?php
return [
    /**
     * Required Data From app.audit.sh
     */
    'app_id'          => env('AUDIT_APP_ID',''),
    'connection_key'  => env('AUDIT_CONNECTION_KEY', 'missing'),

    /**
     * Optional Connection data
     * Only change if on enterprise plan and instructed todo so
     */
    'connection_host' => env('AUDIT_CONNECTION_HOST', 'ingest.audit.sh'),
    'connection_port' => env('AUDIT_CONNECTION_PORT', 42740),

    /**
     * Define the stage the application is running, used for filtering
     */
    'stage'           => env('AUDIT_APM_STAGE', env('APP_ENV', 'production')),

    /**
     * Easy way to turn off monitoring
     */
    'enabled'         => env('AUDIT_APM_ENABLED', true),

    /**
     * Override the transporter method, Currently support
     * logger -> Goes to log
     * udp -> Uses a socket to perform a request ** RECOMMENCED **
     * custom => You can pass a class name which we will load from the container
     */
    'transporter'     => 'udp',
];
