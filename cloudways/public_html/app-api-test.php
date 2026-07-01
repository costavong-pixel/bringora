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
    'message' => 'Click Run App API Test to call api.php through the same JSON path used by the browser.',
    'duration_ms' => '',
    'body_preview' => '',
];

if ($testRan) {
    if (!extension_loaded('curl')) {
        $result['state'] = 'fail';
        $result['message'] = 'PHP cURL extension is not loaded on the server.';
    } else {
        $token = bringora_beta_access_token($config);
        $payload = [
            'prompt' => 'Reply with only OK. This is a protected app API diagnostic test.',
            'mode' => 'write',
            'local_context' => [
                'style' => 'short and direct',
                'constraints' => 'diagnostic test only',
            ],
        ];
        $started = microtime(true);
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'https';
        $host = (string)($_SERVER['HTTP_HOST'] ?? 'bringora.barndai.com');
        $url = $scheme . '://' . $host . '/api.php';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-Bringora-Access: ' . $token,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 45,
        ]);
        $body = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $duration = (int)round((microtime(true) - $started) * 1000);
        $preview = is_string($body) ? substr($body, 0, 1200) : '';

        if ($body === false) {
            $result = ['state'=>'fail','http_code'=>(string)$httpCode,'message'=>'App API connection failed: ' . $curlError,'duration_ms'=>(string)$duration,'body_preview'=>''];
        } else {
            $decoded = json_decode((string)$body, true);
            if ($httpCode >= 200 && $httpCode < 300 && is_array($decoded) && !empty($decoded['success'])) {
                $result = ['state'=>'ok','http_code'=>(string)$httpCode,'message'=>'api.php returned success. Full Generate path works from the server.','duration_ms'=>(string)$duration,'body_preview'=>$preview];
            } elseif (is_array($decoded)) {
                $result = ['state'=>'fail','http_code'=>(string)$httpCode,'message'=>(string)($decoded['error'] ?? 'api.php returned JSON but not success.'),'duration_ms'=>(string)$duration,'body_preview'=>$preview];
            } else {
                $result = ['state'=>'fail','http_code'=>(string)$httpCode,'message'=>'api.php returned a non-JSON response.','duration_ms'=>(string)$duration,'body_preview'=>$preview];
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
<title>Bringora App API Test</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.row{display:grid;grid-template-columns:180px 1fr;gap:12px;border-bottom:1px solid #e5e7eb;padding:12px 0}.row:last-child{border-bottom:0}.label{font-weight:bold}.ok{color:#166534;font-weight:bold}.fail{color:#b91c1c;font-weight:bold}.not{color:#92400e;font-weight:bold}.btn{background:#2563eb;color:#fff;border:0;border-radius:12px;padding:13px 16px;font-weight:bold}.box{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:14px;overflow:auto}@media(max-width:760px){body{padding:16px}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>App API Test</h1>
<p class="small">Protected diagnostic page. This calls api.php with a tiny Generate request through the same JSON/header flow used by the browser. It does not print secrets.</p>
<form method="post"><button class="btn" type="submit">Run App API Test</button></form>
</section>
<section class="card">
<div class="row"><div class="label">State</div><div class="<?php echo $result['state'] === 'ok' ? 'ok' : ($result['state'] === 'fail' ? 'fail' : 'not'); ?>"><?php echo h($result['state']); ?></div></div>
<div class="row"><div class="label">HTTP code</div><div><?php echo h($result['http_code']); ?></div></div>
<div class="row"><div class="label">Duration</div><div><?php echo h($result['duration_ms'] !== '' ? $result['duration_ms'] . ' ms' : ''); ?></div></div>
<div class="row"><div class="label">Message</div><div><?php echo h($result['message']); ?></div></div>
</section>
<?php if ($result['body_preview'] !== ''): ?>
<section class="card">
<h2>Response preview</h2>
<div class="box"><?php echo h($result['body_preview']); ?></div>
</section>
<?php endif; ?>
<section class="card">
<h2>How to read this</h2>
<p class="small">If Provider Test passes but App API Test fails, the issue is likely api.php auth, database usage logging, JSON handling, or request shape. If App API Test passes but browser Generate fails, the issue is likely browser-side JavaScript, cookies, or blocked request behavior.</p>
</section>
</main>
</body>
</html>
