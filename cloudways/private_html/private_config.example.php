<?php
return [
    'BETA_PASSWORD' => 'change-this-password',
    'DEEPSEEK_SECRET' => 'paste-secret-here',
    'DEEPSEEK_MODEL' => 'deepseek-chat',
    'MAX_INPUT_CHARS' => 4000,
    'MAX_OUTPUT_TOKENS' => 650,
    'MAX_RESPONSE_CHARS' => 8000,
    'DAILY_REQUEST_LIMIT' => 25,
    'MONTHLY_REQUEST_LIMIT' => 500,
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'bringora',
    'DB_USER' => 'bringora_user',
    'DB_PASSWORD' => 'change-this-database-password',
    'DB_CHARSET' => 'utf8mb4',
    'APPSUMO_CODES' => [
        'BRINGORA-TIER1-DEMO' => ['tier' => 'tier1', 'daily_limit' => 50, 'monthly_limit' => 500],
        'BRINGORA-TIER2-DEMO' => ['tier' => 'tier2', 'daily_limit' => 100, 'monthly_limit' => 1500],
    ],
    'DEEPSEEK_TEMPERATURE' => 0.4,
    'SUPPORT_EMAIL' => 'support@example.com',
];
