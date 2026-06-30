<?php
$statusPath = __DIR__ . '/../private_html/deploy_status.json';
$status = [];
if (file_exists($statusPath)) {
    $decoded = json_decode((string)file_get_contents($statusPath), true);
    if (is_array($decoded)) {
        $status = $decoded;
    }
}
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$websiteReady = (bool)($status['website_ready'] ?? false);
$apiState = (string)($status['api_smoke_status'] ?? 'not checked');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Status</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.ok{color:#166534;font-weight:bold}.warn{color:#92400e;font-weight:bold}.bad{color:#b91c1c;font-weight:bold}.row{display:grid;grid-template-columns:220px 1fr;gap:12px;border-bottom:1px solid #e5e7eb;padding:12px 0}.row:last-child{border-bottom:0}.label{font-weight:bold}.small{font-size:14px;color:#64748b;line-height:1.5}@media(max-width:700px){body{padding:16px}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Bringora Deployment Status</h1>
<p class="small">This page is written by GitHub Actions after deploy, so you do not need to manually track whether the website files are ready.</p>
<p>
<?php if ($websiteReady): ?>
<span class="ok">Website deploy ready</span>
<?php else: ?>
<span class="warn">No successful deploy status recorded yet</span>
<?php endif; ?>
</p>
</section>
<section class="card">
<div class="row"><div class="label">Website files</div><div><?php echo $websiteReady ? '<span class="ok">Ready</span>' : '<span class="warn">Unknown</span>'; ?></div></div>
<div class="row"><div class="label">Generate/API</div><div><span class="warn"><?php echo h($apiState); ?></span><br><span class="small">Generate can be parked while website/product pages continue.</span></div></div>
<div class="row"><div class="label">Commit</div><div><?php echo h($status['commit'] ?? 'unknown'); ?></div></div>
<div class="row"><div class="label">Run</div><div><?php if (!empty($status['run_url'])): ?><a href="<?php echo h($status['run_url']); ?>">GitHub Actions run #<?php echo h($status['run_number'] ?? ''); ?></a><?php else: ?>unknown<?php endif; ?></div></div>
<div class="row"><div class="label">Deployed at</div><div><?php echo h($status['deployed_at_utc'] ?? 'unknown'); ?> UTC</div></div>
</section>
</main>
</body>
</html>
