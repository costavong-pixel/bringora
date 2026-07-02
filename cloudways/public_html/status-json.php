<?php
$statusPath = __DIR__ . '/../private_html/deploy_status.json';
$status = [];
if (file_exists($statusPath)) {
    $decoded = json_decode((string)file_get_contents($statusPath), true);
    if (is_array($decoded)) {
        $status = $decoded;
    }
}

$host = (string)($_SERVER['HTTP_HOST'] ?? 'bringora.barndai.com');
$baseUrl = 'https://' . $host;
$websiteReady = (bool)($status['website_ready'] ?? false);
$apiState = (string)($status['api_smoke_status'] ?? 'not checked');
$commit = (string)($status['commit'] ?? 'unknown');
$deployedAt = (string)($status['deployed_at_utc'] ?? 'unknown');
$dedupeKey = substr(hash('sha256', $commit . '|' . $apiState . '|' . $deployedAt), 0, 16);

$payload = [
    'ok' => true,
    'service' => 'bringora',
    'environment' => 'production',
    'checked_at_utc' => gmdate('c'),
    'website_ready' => $websiteReady,
    'agent_ready' => $websiteReady,
    'recommended_action' => $websiteReady ? 'create_github_issue' : 'wait_for_deploy',
    'reason' => $websiteReady ? 'Website deploy is ready.' : 'No successful deploy status recorded yet.',
    'deploy' => [
        'commit' => $commit,
        'run_number' => (string)($status['run_number'] ?? 'unknown'),
        'run_url' => (string)($status['run_url'] ?? ''),
        'deployed_at_utc' => $deployedAt,
    ],
    'generate_api' => [
        'state' => $apiState,
        'diagnostic_path' => [
            $baseUrl . '/api-debug.php',
            $baseUrl . '/db-debug.php',
            $baseUrl . '/provider-test.php',
            $baseUrl . '/app-api-test.php',
            $baseUrl . '/browser-api-test.php',
            $baseUrl . '/index.php',
        ],
    ],
    'links' => [
        'status' => $baseUrl . '/status.php',
        'status_json' => $baseUrl . '/status-json.php',
        'test_results' => $baseUrl . '/test-results.php',
        'api_debug' => $baseUrl . '/api-debug.php',
        'db_debug' => $baseUrl . '/db-debug.php',
        'provider_test' => $baseUrl . '/provider-test.php',
        'app_api_test' => $baseUrl . '/app-api-test.php',
        'browser_api_test' => $baseUrl . '/browser-api-test.php',
        'app' => $baseUrl . '/index.php',
    ],
    'github_issue' => [
        'dedupe_key' => $dedupeKey,
        'title' => 'Bringora agent task ' . $dedupeKey,
        'body' => 'Bringora status JSON reports website_ready=true. Dedupe key: ' . $dedupeKey . '. Status: ' . $baseUrl . '/status.php',
    ],
];

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
