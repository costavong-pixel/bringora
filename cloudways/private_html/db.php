<?php
function bringora_db(array $config): PDO
{
    $dsn = trim((string)($config['DB_DSN'] ?? ''));
    if ($dsn === '') {
        $host = (string)($config['DB_HOST'] ?? '127.0.0.1');
        $name = (string)($config['DB_NAME'] ?? '');
        $charset = (string)($config['DB_CHARSET'] ?? 'utf8mb4');
        if ($name === '') {
            throw new RuntimeException('Database is not configured. Set DB_NAME in private_config.php.');
        }
        $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";
    }

    return new PDO($dsn, (string)($config['DB_USER'] ?? ''), (string)($config['DB_PASSWORD'] ?? ''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

function bringora_hash_value(string $value, string $salt): string
{
    if ($salt === '' || strpos($salt, 'change-this') === 0) {
        throw new RuntimeException('USAGE_HASH_SALT must be set to a unique private value in private_config.php.');
    }

    return hash_hmac('sha256', $value, $salt);
}

function bringora_client_ip(): string
{
    $ip = (string)($_SERVER['HTTP_CF_CONNECTING_IP'] ?? '');
    if ($ip === '' && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', (string)$_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($parts[0]);
    }
    if ($ip === '') {
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    }

    return $ip;
}

function bringora_access_key(array $config): string
{
    $type = (string)($_SESSION['bringora_access_type'] ?? 'beta');
    $usageSalt = (string)($config['USAGE_HASH_SALT'] ?? '');
    if ($type === 'appsumo' && !empty($_SESSION['bringora_appsumo_code'])) {
        $codeSalt = (string)($config['CODE_HASH_SALT'] ?? $usageSalt);
        $codeHash = bringora_hash_value(strtoupper((string)$_SESSION['bringora_appsumo_code']), $codeSalt);
        return 'appsumo:' . $codeHash;
    }

    $fingerprint = bringora_client_ip() . '|' . (string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
    return 'beta_ip:' . bringora_hash_value($fingerprint, $usageSalt);
}

function bringora_daily_limit(array $config): int
{
    return max(1, (int)($_SESSION['bringora_daily_limit'] ?? ($config['DAILY_REQUEST_LIMIT'] ?? 25)));
}

function bringora_monthly_limit(array $config): int
{
    return max(1, (int)($_SESSION['bringora_monthly_limit'] ?? ($config['MONTHLY_REQUEST_LIMIT'] ?? 500)));
}

function bringora_period_counts(PDO $db, string $accessKey): array
{
    $daily = $db->prepare("SELECT COUNT(*) FROM usage_logs WHERE access_key = :access_key AND status = 'success' AND DATE(created_at) = UTC_DATE()");
    $daily->execute(['access_key' => $accessKey]);

    $monthly = $db->prepare("SELECT COUNT(*) FROM usage_logs WHERE access_key = :access_key AND status = 'success' AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(UTC_TIMESTAMP(), '%Y-%m')");
    $monthly->execute(['access_key' => $accessKey]);

    return [
        'daily' => (int)$daily->fetchColumn(),
        'monthly' => (int)$monthly->fetchColumn(),
    ];
}
