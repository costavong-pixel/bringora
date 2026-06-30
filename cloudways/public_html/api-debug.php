<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}
$config = require $configPath;
require_once __DIR__ . '/../private_html/auth.php';
require_once __DIR__ . '/../private_html/db.php';

$authPayload = bringora_read_auth_payload($config);
if ($authPayload === null) {
    header('Location: index.php');
    exit;
}
bringora_apply_auth_payload($authPayload);
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$checks = [];
$checks[] = ['PHP version', PHP_VERSION, 'info'];
$checks[] = ['cURL extension', extension_loaded('curl') ? 'loaded' : 'missing', extension_loaded('curl') ? 'ok' : 'bad'];
$checks[] = ['PDO extension', extension_loaded('pdo') ? 'loaded' : 'missing', extension_loaded('pdo') ? 'ok' : 'bad'];
$checks[] = ['DeepSeek key configured', trim((string)($config['DEEPSEEK_SECRET'] ?? '')) !== '' ? 'yes' : 'no', trim((string)($config['DEEPSEEK_SECRET'] ?? '')) !== '' ? 'ok' : 'bad'];
$checks[] = ['DeepSeek model', (string)($config['DEEPSEEK_MODEL'] ?? 'not set'), 'info'];
$checks[] = ['DB host', (string)($config['DB_HOST'] ?? 'not set'), 'info'];
$checks[] = ['DB name present', trim((string)($config['DB_NAME'] ?? '')) !== '' ? 'yes' : 'no', trim((string)($config['DB_NAME'] ?? '')) !== '' ? 'ok' : 'bad'];
try {
    $db = bringora_db($config);
    $db->query('SELECT 1');
    $checks[] = ['Database connection', 'ok', 'ok'];
} catch (Throwable $e) {
    $checks[] = ['Database connection', $e->getMessage(), 'bad'];
}
$checks[] = ['Auth payload type', (string)($authPayload['type'] ?? 'beta'), 'info'];
$checks[] = ['Daily limit', (string)bringora_daily_limit($config), 'info'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora API Debug</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.row{display:grid;grid-template-columns:220px 1fr 80px;gap:12px;border-bottom:1px solid #e5e7eb;padding:12px 0}.row:last-child{border-bottom:0}.label{font-weight:bold}.ok{color:#166534;font-weight:bold}.bad{color:#b91c1c;font-weight:bold}.info{color:#475569;font-weight:bold}.small{font-size:14px;color:#64748b;line-height:1.5}@media(max-width:760px){body{padding:16px}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Bringora API Debug</h1>
<p class="small">Protected beta diagnostic page. It shows pass/fail status only and does not print API keys or passwords.</p>
</section>
<section class="card">
<?php foreach ($checks as $check): ?>
<div class="row"><div class="label"><?php echo h($check[0]); ?></div><div><?php echo h($check[1]); ?></div><div class="<?php echo h($check[2]); ?>"><?php echo h($check[2]); ?></div></div>
<?php endforeach; ?>
</section>
</main>
</body>
</html>
