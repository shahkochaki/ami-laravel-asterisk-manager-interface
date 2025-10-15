<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AMI Connection Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Asterisk Manager Interface connection
    |
    */
    'host' => env('AMI_HOST', '127.0.0.1'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME', null),
    'secret' => env('AMI_SECRET', null),

    /*
    |--------------------------------------------------------------------------
    | Connection Pool Settings
    |--------------------------------------------------------------------------
    |
    | Settings for connection pooling and management
    |
    */
    'connection' => [
        'timeout' => env('AMI_CONNECTION_TIMEOUT', 10),
        'enable_pooling' => env('AMI_ENABLE_POOLING', true),
        'max_connections' => env('AMI_MAX_CONNECTIONS', 5),
        'heartbeat_interval' => env('AMI_HEARTBEAT_INTERVAL', 30),
        'auto_reconnect' => env('AMI_AUTO_RECONNECT', true),
        'retry_attempts' => env('AMI_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('AMI_RETRY_DELAY', 5), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Dongle Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for Chan Dongle functionality (SMS, USSD)
    |
    */
    'dongle' => [
        'sms' => [
            'device' => env('AMI_SMS_DEVICE', 'dongle0'),
            'pdu_threshold' => env('AMI_SMS_PDU_THRESHOLD', 160),
            'max_retries' => env('AMI_SMS_MAX_RETRIES', 3),
            'delay_between_messages' => env('AMI_SMS_DELAY', 500), // milliseconds
            'bulk_batch_size' => env('AMI_SMS_BULK_BATCH_SIZE', 100),
        ],
        'ussd' => [
            'device' => env('AMI_USSD_DEVICE', 'dongle0'),
            'timeout' => env('AMI_USSD_TIMEOUT', 30), // seconds
        ],
        'devices' => [
            // Add your dongle devices here
            // 'dongle0' => ['name' => 'Main Dongle', 'sim_slot' => 1],
            // 'dongle1' => ['name' => 'Backup Dongle', 'sim_slot' => 2],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for AMI event logging
    |
    */
    'logging' => [
        'enabled' => env('AMI_LOGGING_ENABLED', true),
        'channel' => env('AMI_LOG_CHANNEL', 'ami'),
        'level' => env('AMI_LOG_LEVEL', 'info'),
        'events' => [
            'call_events' => env('AMI_LOG_CALL_EVENTS', true),
            'sms_events' => env('AMI_LOG_SMS_EVENTS', true),
            'system_events' => env('AMI_LOG_SYSTEM_EVENTS', true),
            'queue_events' => env('AMI_LOG_QUEUE_EVENTS', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Handlers
    |--------------------------------------------------------------------------
    |
    | Register custom event handlers for AMI events
    |
    */
    'events' => [
        // Call Events
        'Dial' => [
            // 'App\Listeners\CallDialedListener',
        ],
        'Hangup' => [
            // 'App\Listeners\CallHangupListener',
        ],
        'NewChannel' => [
            // 'App\Listeners\NewChannelListener',
        ],
        'Bridge' => [
            // 'App\Listeners\CallBridgedListener',
        ],
        
        // SMS Events
        'DongleSMSStatus' => [
            // 'App\Listeners\SmsStatusListener',
        ],
        'DongleNewSMS' => [
            // 'App\Listeners\NewSmsListener',
        ],

        // Queue Events
        'QueueMember' => [
            // 'App\Listeners\QueueMemberListener',
        ],
        'QueueParams' => [
            // 'App\Listeners\QueueParamsListener',
        ],

        // System Events
        'Reload' => [
            // 'App\Listeners\SystemReloadListener',
        ],
        'Shutdown' => [
            // 'App\Listeners\SystemShutdownListener',
        ],
        'PeerStatus' => [
            // 'App\Listeners\PeerStatusListener',
        ],

        // Agent Events
        'AgentConnect' => [],
        'AgentComplete' => [],
        'Agentlogin' => [],
        'Agentlogoff' => [],
        'Agents' => [],

        // Other Events
        'AsyncAGI' => [],
        'CDR' => [],
        'CEL' => [],
        'ChannelUpdate' => [],
        'CoreShowChannel' => [],
        'CoreShowChannelsComplete' => [],
        'DAHDIShowChannelsComplete' => [],
        'DAHDIShowChannels' => [],
        'DBGetResponse' => [],
        'DTMF' => [],
        'DongleDeviceEntry' => [],
        'DongleShowDevicesComplete' => [],
        'DongleUSSDStatus' => [],
        'ExtensionStatus' => [],
        'FullyBooted' => [],
        'Hangup' => [],
        'Hold' => [],
        'Join' => [],
        'Leave' => [],
        'Link' => [],
        'ListDialplan' => [],
        'Masquerade' => [],
        'MessageWaiting' => [],
        'MusicOnHold' => [],
        'NewAccountCode' => [],
        'NewCallerid' => [],
        'NewExten' => [],
        'NewState' => [],
        'Newchannel' => [],
        'OriginateResponse' => [],
        'ParkedCall' => [],
        'ParkedCallTimeOut' => [],
        'ParkedCallsComplete' => [],
        'QueueEntry' => [],
        'QueueMemberAdded' => [],
        'QueueMemberPaused' => [],
        'QueueMemberRemoved' => [],
        'QueueMemberStatus' => [],
        'QueueStatusComplete' => [],
        'QueueSummary' => [],
        'QueueSummaryComplete' => [],
        'Registry' => [],
        'Rename' => [],
        'RTCPReceived' => [],
        'RTCPSent' => [],
        'RTPReceiverStat' => [],
        'RTPSenderStat' => [],
        'ShowDialPlanComplete' => [],
        'Status' => [],
        'StatusComplete' => [],
        'Transfer' => [],
        'Unhold' => [],
        'Unlink' => [],
        'UnParkedCall' => [],
        'UserEvent' => [],
        'VarSet' => [],
        'AGIExec' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Security related configurations
    |
    */
    'security' => [
        'enable_encryption' => env('AMI_ENABLE_ENCRYPTION', false),
        'allowed_ips' => env('AMI_ALLOWED_IPS', '*'), // comma separated or * for all
        'rate_limiting' => [
            'enabled' => env('AMI_RATE_LIMITING', false),
            'max_requests_per_minute' => env('AMI_MAX_REQUESTS_PER_MINUTE', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    |
    | Performance and optimization settings
    |
    */
    'performance' => [
        'cache_responses' => env('AMI_CACHE_RESPONSES', false),
        'cache_ttl' => env('AMI_CACHE_TTL', 300), // seconds
        'async_processing' => env('AMI_ASYNC_PROCESSING', true),
        'max_concurrent_requests' => env('AMI_MAX_CONCURRENT_REQUESTS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Settings
    |--------------------------------------------------------------------------
    |
    | Settings for development and debugging
    |
    */
    'development' => [
        'debug_mode' => env('AMI_DEBUG_MODE', env('APP_DEBUG', false)),
        'mock_responses' => env('AMI_MOCK_RESPONSES', false),
        'log_raw_data' => env('AMI_LOG_RAW_DATA', false),
        'simulate_events' => env('AMI_SIMULATE_EVENTS', false),
    ],
];