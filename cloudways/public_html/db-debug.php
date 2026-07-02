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

$tables = [
    'usage_logs' => ['access_key','category','input_chars','output_chars','status','created_at'],
    'saved_outputs' => ['id','access_key','category','title','output_text','created_at'],
    'redemption_codes' => ['code_value','tier','daily_limit','monthly_limit'],
];
$checks = [];
$overall = 'ok';

try {
    $db = bringora_db($config);
    foreach ($tables as $table => $requiredColumns) {
        try {
            $stmt = $db->query('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`');
            $columns = $stmt ? $stmt->fetchAll() : [];
            $found = [];
            foreach ($columns as $column) {
                if (isset($column['Field'])) {
                    $found[] = (string)$column['Field'];
                }
            }
            $missing = array_values(array_diff($requiredColumns, $found));
            if ($missing) {
                $checks[] = [$table, 'missing columns: ' . implode(', ', $missing), 'bad'];
                $overall = 'bad';
            } else {
                $checks[] = [$table, 'ok (' . count($found) . ' columns found)', 'ok'];
            }
        } catch (Throwable $e) {
            $checks[] = [$table, 'table missing or unreadable: ' . $e->getMessage(), 'bad'];
            $overall = 'bad';
        }
    }
} catch (Throwable $e) {
    $checks[] = ['database connection', $e->getMessage(), 'bad'];
    $overall = 'bad';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora DB Debug</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.row{display:grid;grid-template-columns:220px 1fr 80px;gap:12px;border-bottom:1px solid #e5e7eb;padding:12px 0}.row:last-child{border-bottom:0}.label{font-weight:bold}.ok{color:#166534;font-weight:bold}.bad{color:#b91c1c;font-weight:bold}.btn{display:inline-block;background:#2563eb;color:#fff;text-decoration:none;border-radius:12px;padding:12px 15px;font-weight:bold;margin-right:8px;margin-top:8px}.ghost{background:#111827}@media(max-width:760px){body{padding:16px}.row{grid-template-columns:1fr}.btn{display:block;margin-right:0}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Database Debug</h1>
<p class="small">Protected diagnostic page. It checks required table/column shape without printing database credentials.</p>
<p class="<?php echo $overall === 'ok' ? 'ok' : 'bad'; ?>">Overall: <?php echo h($overall); ?></p>
<a class="btn" href="api-debug.php">API Debug</a><a class="btn ghost" href="app-api-test.php">App API Test</a>
</section>
<section class="card">
<?php foreach ($checks as $check): ?>
<div class="row"><div class="label"><?php echo h($check[0]); ?></div><div><?php echo h($check[1]); ?></div><div class="<?php echo h($check[2]); ?>"><?php echo h($check[2]); ?></div></div>
<?php endforeach; ?>
</section>
<section class="card">
<h2>How to read this</h2>
<p class="small">If Database Debug fails, Generate or Save Output can fail even when DeepSeek works. Fix missing tables or columns before retesting App API Test.</p>
</section>
</main>
</body>
</html>
