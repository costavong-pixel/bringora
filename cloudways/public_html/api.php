<?php
header('Content-Type: application/json');

$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Private config file not found.']);
    exit;
}

$config = require $configPath;

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit;
}

$prompt = trim($data['prompt'] ?? '');
$mode = trim($data['mode'] ?? 'write');

if ($prompt === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Input is empty.']);
    exit;
}

$result = "ORGANIZED SUMMARY\n";
$result .= "Bringora received your input and selected category: " . $mode . "\n\n";
$result .= "STRUCTURE OR CATEGORY BREAKDOWN\n";
$result .= "This is a safe stub response. Connect DeepSeek in the production API file.\n\n";
$result .= "ACTION STEPS\n";
$result .= "1. Confirm the category.\n2. Replace this stub with the DeepSeek direct call.\n3. Test with real daily examples.\n\n";
$result .= "SIMPLE RECOMMENDATION\n";
$result .= "Keep output text-only and action-focused.\n\n";
$result .= "NEXT BEST ACTION\n";
$result .= "Finish the provider connection after the private config is ready.";

echo json_encode(['success' => true, 'result' => $result]);
