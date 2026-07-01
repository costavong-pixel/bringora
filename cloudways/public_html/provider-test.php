<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}
$config = require $configPath;
require_once __DIR__ . '/../private_html/auth.php';

$authPayload = bringora_read_auth_payload($config);
if ($authPayload === null) {
    header('Location: index.php');
    exit;
}
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

$testRan = ($_SERVER['REQUEST_METHOD'] === 'POST');
$result = [
    'state' => 'not run',
    'http_code' => '',
    'message' => 'Click Run Provider Test to make one tiny DeepSeek request.',
    'duration_ms' => '',
];

if ($testRan) {
    $apiKey = trim((string)($config['DEEPSEEK_SECRET'] ?? ''));
    $apiUrl = trim((string)($config['DEEPSEEK_API_URL'] ?? 'https://api.deepseek.com/chat/completions'));
    $model = (string)($config['DEEPSEEK_MODEL'] ?? 'deepseek-chat');

    if ($apiKey === '') {
        $result = ['state'=>'fail','http_code'=>'','message'=>'DeepSeek key is missing in private_config.php.','duration_ms'=>''];
    } elseif (!extension_loaded('curl')) {
        $result = ['state'=>'fail','http_code'=>'','message'=>'PHP cURL extension is not loaded on the server.','duration_ms'=>''];
    } else {
        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'Reply with exactly OK.'],
                ['role' => 'user', 'content' => 'Deployment provider test.'],
            ],
            'temperature' => 0,
            'max_tokens' => 5,
            'stream' => false,
        ];
        $started = microtime(true);
        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
        ]);
        $body = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $duration = (int)round((microtime(true) - $started) * 1000);

        if ($body === false) {
            $result = ['state'=>'fail','http_code'=>(string)$httpCode,'message'=>'Provider connection failed: ' . $curlError,'duration_ms'=>(string)$duration];
        } else {
            $decoded = json_decode((string)$body, true);
            if ($httpCode >= 200 && $httpCode < 300) {
                $reply = trim((string)($decoded['choices'][0]['message']['content'] ?? ''));
                $result = ['state'=>'ok','http_code'=>(string)$httpCode,'message'=>'Provider replied: ' . ($reply !== '' ? $reply : '(empty reply)'),'duration_ms'=>(string)$duration];
            } else {
                $providerMessage = (string)($decoded['error']['message'] ?? 'Provider returned a non-success response.');
                $result = ['state'=>'fail','http_code'=>(string)$httpCode,'message'=>$providerMessage,'duration_ms'=>(string)$duration];
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Provider Test</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.row{display:grid;grid-template-columns:180px 1fr;gap:12px;border-bottom:1px solid #e5e7eb;padding:12px 0}.row:last-child{border-bottom:0}.label{font-weight:bold}.ok{color:#166534;font-weight:bold}.fail{color:#b91c1c;font-weight:bold}.not{color:#92400e;font-weight:bold}.btn{background:#2563eb;color:#fff;border:0;border-radius:12px;padding:13px 16px;font-weight:bold}@media(max-width:760px){body{padding:16px}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Provider Test</h1>
<p class="small">Protected diagnostic page. This makes one tiny DeepSeek request to test whether the provider connection works. It does not print API keys.</p>
<form method="post"><button class="btn" type="submit">Run Provider Test</button></form>
</section>
<section class="card">
<div class="row"><div class="label">State</div><div class="<?php echo $result['state'] === 'ok' ? 'ok' : ($result['state'] === 'fail' ? 'fail' : 'not'); ?>"><?php echo h($result['state']); ?></div></div>
<div class="row"><div class="label">HTTP code</div><div><?php echo h($result['http_code']); ?></div></div>
<div class="row"><div class="label">Duration</div><div><?php echo h($result['duration_ms'] !== '' ? $result['duration_ms'] . ' ms' : ''); ?></div></div>
<div class="row"><div class="label">Message</div><div><?php echo h($result['message']); ?></div></div>
</section>
<section class="card">
<h2>How to read this</h2>
<p class="small">If API Debug is green but Provider Test fails, the problem is likely provider key, provider URL, model name, timeout, or provider account/billing. If Provider Test passes but Generate fails, the blocker is likely app request/auth/database flow.</p>
</section>
</main>
</body>
</html>
