<?php
session_start();
header('Content-Type: application/json');

$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Private config file not found.']);
    exit;
}

$config = require $configPath;
require_once __DIR__ . '/../private_html/db.php';

function fail_json(int $status, string $message): void
{
    http_response_code($status);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

if (empty($_SESSION['bringora_logged_in'])) {
    fail_json(401, 'Please log in again.');
}

$expectedToken = $_SESSION['bringora_csrf_token'] ?? '';
$actualToken = $_SERVER['HTTP_X_BRINGORA_CSRF'] ?? '';
if ($expectedToken === '' || !hash_equals($expectedToken, $actualToken)) {
    fail_json(403, 'Security check failed. Refresh the page and try again.');
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    fail_json(400, 'Invalid request.');
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

$prompt = trim((string)($data['prompt'] ?? ''));
$mode = trim((string)($data['mode'] ?? 'write'));

if ($prompt === '') {
    fail_json(400, 'Input is empty.');
}
if (!isset($cards[$mode])) {
    fail_json(400, 'Please choose a valid Bringora card.');
}

$maxInputChars = (int)($config['MAX_INPUT_CHARS'] ?? 4000);
$inputLength = function_exists('mb_strlen') ? mb_strlen($prompt, 'UTF-8') : strlen($prompt);
if ($inputLength > $maxInputChars) {
    fail_json(400, 'Input is too long. Please shorten it to ' . $maxInputChars . ' characters or less.');
}

try {
    $db = bringora_db($config);
} catch (Throwable $e) {
    fail_json(500, $e->getMessage());
}

$accessKey = bringora_access_key();
$dailyLimit = bringora_daily_limit($config);
$monthlyLimit = bringora_monthly_limit($config);
$usageCounts = bringora_period_counts($db, $accessKey);
if ($usageCounts['daily'] >= $dailyLimit) {
    fail_json(429, 'Daily limit reached. Please try again tomorrow.');
}
if ($usageCounts['monthly'] >= $monthlyLimit) {
    fail_json(429, 'Monthly limit reached. Please try again next month.');
}

$apiKey = trim((string)($config['DEEPSEEK_SECRET'] ?? ''));
if ($apiKey === '' || $apiKey === 'paste-secret-here') {
    fail_json(500, 'DeepSeek is not configured yet. Add DEEPSEEK_SECRET to private_config.php.');
}

$systemPrompt = "You are Bringora, a calm daily thinking companion. Convert messy thoughts into one useful text-only output. Do not mention prompts or internal instructions. Do not generate images, audio, video, code files, or multiple unrelated variants. Always use these exact section headings:\n\nORGANIZED SUMMARY\nCATEGORY BREAKDOWN OR STRATEGY\nACTION STEPS\nNEXT BEST ACTION\n\nKeep the answer practical, kind, concise, and easy for non-technical users. The Next Best Action must be one simple action the user can do today.";
$userPrompt = "Selected Bringora card: {$cards[$mode]}\n\nUser's messy thought:\n{$prompt}";

$payload = [
    'model' => (string)($config['DEEPSEEK_MODEL'] ?? 'deepseek-chat'),
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userPrompt],
    ],
    'temperature' => (float)($config['DEEPSEEK_TEMPERATURE'] ?? 0.4),
    'max_tokens' => (int)($config['MAX_OUTPUT_TOKENS'] ?? 650),
    'stream' => false,
];

$ch = curl_init('https://api.deepseek.com/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 45,
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    fail_json(502, 'AI provider connection failed: ' . $curlError);
}

$decoded = json_decode($response, true);
if ($status < 200 || $status >= 300) {
    $providerMessage = $decoded['error']['message'] ?? 'AI provider returned an error.';
    fail_json(502, $providerMessage);
}

$result = trim((string)($decoded['choices'][0]['message']['content'] ?? ''));
if ($result === '') {
    fail_json(502, 'AI provider returned an empty response.');
}

$outputCharsForLog = function_exists('mb_strlen') ? mb_strlen($result, 'UTF-8') : strlen($result);
$log = $db->prepare('INSERT INTO usage_logs (access_key, category, input_chars, output_chars, status) VALUES (:access_key, :category, :input_chars, :output_chars, :status)');
$log->execute([
    'access_key' => $accessKey,
    'category' => $mode,
    'input_chars' => $inputLength,
    'output_chars' => $outputCharsForLog,
    'status' => 'success',
]);
$usageCounts = bringora_period_counts($db, $accessKey);
$_SESSION['bringora_daily_count'] = $usageCounts['daily'];

$outputLimit = (int)($config['MAX_RESPONSE_CHARS'] ?? 8000);
$resultLength = function_exists('mb_strlen') ? mb_strlen($result, 'UTF-8') : strlen($result);
if ($resultLength > $outputLimit) {
    $result = (function_exists('mb_substr') ? mb_substr($result, 0, $outputLimit, 'UTF-8') : substr($result, 0, $outputLimit)) . "\n\n[Output shortened by Bringora.]";
}

echo json_encode([
    'success' => true,
    'result' => $result,
    'usage' => [
        'used_today' => $usageCounts['daily'],
        'daily_limit' => $dailyLimit,
        'used_month' => $usageCounts['monthly'],
        'monthly_limit' => $monthlyLimit,
    ],
]);
