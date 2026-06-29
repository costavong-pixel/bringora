<?php
function bringora_is_https(): bool
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

function bringora_session_options(): array
{
    return [
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => bringora_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function bringora_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name('BRINGORASESSID');
    session_set_cookie_params(bringora_session_options());
    session_start();
}

function bringora_destroy_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        bringora_start_session();
    }

    $_SESSION = [];
    $options = bringora_session_options();
    setcookie(session_name(), '', time() - 3600, $options['path'], $options['domain'], $options['secure'], $options['httponly']);
    session_destroy();
}
