<?php
function bringora_auth_is_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        return true;
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
        return true;
    }
    return false;
}

function bringora_auth_secret(array $config): string
{
    $secret = (string)($config['CODE_HASH_SALT'] ?? '');
    if ($secret === '' || strpos($secret, 'change-this') === 0) {
        $secret = (string)($config['USAGE_HASH_SALT'] ?? '');
    }
    if ($secret === '' || strpos($secret, 'change-this') === 0) {
        $secret = (string)($config['BETA_PASSWORD'] ?? '');
    }
    return $secret;
}

function bringora_b64url_encode(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

function bringora_b64url_decode(string $value)
{
    $pad = strlen($value) % 4;
    if ($pad > 0) {
        $value .= str_repeat('=', 4 - $pad);
    }
    return base64_decode(strtr($value, '-_', '+/'), true);
}

function bringora_sign_auth_payload(array $payload, array $config): string
{
    $secret = bringora_auth_secret($config);
    if ($secret === '') {
        return '';
    }
    $payload['iat'] = time();
    $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
    if (!is_string($json)) {
        return '';
    }
    $body = bringora_b64url_encode($json);
    $sig = hash_hmac('sha256', $body, $secret);
    return $body . '.' . $sig;
}

function bringora_read_auth_payload(array $config)
{
    $token = (string)($_COOKIE['BRINGORA_AUTH'] ?? '');
    if ($token === '' || strpos($token, '.') === false) {
        return null;
    }
    $parts = explode('.', $token, 2);
    if (count($parts) !== 2) {
        return null;
    }
    $body = $parts[0];
    $sig = $parts[1];
    $secret = bringora_auth_secret($config);
    if ($secret === '') {
        return null;
    }
    $expected = hash_hmac('sha256', $body, $secret);
    if (!hash_equals($expected, $sig)) {
        return null;
    }
    $json = bringora_b64url_decode($body);
    if (!is_string($json)) {
        return null;
    }
    $payload = json_decode($json, true);
    return is_array($payload) ? $payload : null;
}

function bringora_auth_cookie_options(): array
{
    return [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => '/',
        'domain' => '',
        'secure' => bringora_auth_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function bringora_set_auth_cookie(string $token): void
{
    setcookie('BRINGORA_AUTH', $token, bringora_auth_cookie_options());
    $_COOKIE['BRINGORA_AUTH'] = $token;
}

function bringora_clear_auth_cookie(): void
{
    $options = bringora_auth_cookie_options();
    $options['expires'] = time() - 3600;
    setcookie('BRINGORA_AUTH', '', $options);
    unset($_COOKIE['BRINGORA_AUTH']);
}

function bringora_beta_access_token(array $config): string
{
    $betaPassword = (string)($config['BETA_PASSWORD'] ?? '');
    return $betaPassword !== '' ? hash_hmac('sha256', 'bringora_beta_access', $betaPassword) : '';
}

function bringora_access_header_valid(array $config): bool
{
    $expected = bringora_beta_access_token($config);
    $received = (string)($_SERVER['HTTP_X_BRINGORA_ACCESS'] ?? '');
    return $expected !== '' && hash_equals($expected, $received);
}

function bringora_apply_auth_payload($payload): void
{
    $GLOBALS['bringora_auth_payload'] = is_array($payload) ? $payload : ['type' => 'beta'];
}
