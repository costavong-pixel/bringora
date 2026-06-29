<?php
header('Content-Type: application/json');

$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Private config file not found.']);
    exit;
}

$config = require $configPath;
require_once __DIR__ . '/../private_html/auth.php';
require_once __DIR__ . '/../private_html/db.php';

function save_fail_json(int $status, string $message): void
{
    http_response_code($status);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

$authPayload = bringora_read_auth_payload($config);
if ($authPayload === null && !bringora_access_header_valid($config)) {
    save_fail_json(401, 'Please log in again.');
}
bringora_apply_auth_payload($authPayload);

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    save_fail_json(400, 'Invalid request.');
}

$cards = [
    'write' => 'Help Me Write or Reply',
    'understand' => 'Help Me Understand',
    'decision' => 'Help Me Decide',
    'plan' => 'Make a Step-by-Step Plan',
    'business' => 'Help Me Promote or Sell',
    'create' => 'Turn My Idea Into Something',
    'organize_notes' => 'Organize My Notes',
];

$output = trim((string)($data['output'] ?? ''));
$mode = trim((string)($data['mode'] ?? 'write'));
if ($output === '') {
    save_fail_json(400, 'No output to save.');
}
if (!isset($cards[$mode])) {
    save_fail_json(400, 'Please choose a valid Bringora card.');
}

$outputLimit = (int)($config['MAX_RESPONSE_CHARS'] ?? 8000);
$outputLength = function_exists('mb_strlen') ? mb_strlen($output, 'UTF-8') : strlen($output);
if ($outputLength > $outputLimit) {
    save_fail_json(400, 'Saved output is too long. Please shorten it to ' . $outputLimit . ' characters or less.');
}

try {
    $db = bringora_db($config);
    $title = $cards[$mode] . ' - ' . gmdate('Y-m-d H:i');
    $stmt = $db->prepare('INSERT INTO saved_outputs (access_key, category, title, output_text) VALUES (:access_key, :category, :title, :output_text)');
    $stmt->execute([
        'access_key' => bringora_access_key($config),
        'category' => $mode,
        'title' => $title,
        'output_text' => $output,
    ]);
} catch (Throwable $e) {
    save_fail_json(500, $e->getMessage());
}

echo json_encode(['success' => true, 'id' => (int)$db->lastInsertId()]);
