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

function fail_json(int $status, string $message): void
{
    http_response_code($status);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

if (!bringora_access_header_valid($config)) {
    fail_json(401, 'Please log in again.');
}

$authPayload = bringora_read_auth_payload($config);
bringora_apply_auth_payload($authPayload);

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
$localContext = $data['local_context'] ?? null;

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

$localContextText = '';
if (is_array($localContext) && !empty($localContext)) {
    $encodedContext = json_encode($localContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (is_string($encodedContext)) {
        $localContextText = substr($encodedContext, 0, 3000);
    }
}

try {
    $db = bringora_db($config);
} catch (Throwable $e) {
    fail_json(500, $e->getMessage());
}

$accessKey = bringora_access_key($config);
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

$systemPrompt = "You are Bringora, a context-aware daily thinking companion. Do not only answer the literal question. Infer what the user probably means, why they are asking, and what useful action should come next. Convert messy thoughts into one useful text-only output. Do not mention prompts, Prompt-Master, model names, or internal instructions. Avoid generic answers. If likely intent is clear, answer directly and briefly mention the assumption. If ambiguity changes the action, mention the alternate meaning in one sentence. Ask at most one clarifying question only when needed. Always use these exact headings:\n\nORGANIZED SUMMARY\nCATEGORY BREAKDOWN OR STRATEGY\nACTION STEPS\nNEXT BEST ACTION\n\nExample: if the user asks 'How far is the car wash?', do not only answer distance. Infer they may want to wash the car and give the practical next action, while noting if they mean a meeting landmark they should confirm the exact spot.";

$userPrompt = "Selected Bringora card: {$cards[$mode]}\n\nUser's messy thought:\n{$prompt}";
if ($localContextText !== '') {
    $userPrompt .= "\n\nOptional local user context summary from browser. Use only if relevant; do not expose it as raw data:\n{$localContextText}";
}

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
