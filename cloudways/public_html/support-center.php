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
$items = [
    ['title'=>'Website pages not loading', 'text'=>'Open status.php first. If website deploy is ready but one page fails, report the page name and screenshot.'],
    ['title'=>'Generate is not working', 'text'=>'Generate/API is currently parked while product pages continue. Report the exact red error only.'],
    ['title'=>'My Bringora Brain missing data', 'text'=>'The profile is browser-local for now. It may not appear on another phone, browser, or incognito window.'],
    ['title'=>'AppSumo code problem', 'text'=>'Report whether you used beta password or redemption code. Do not send private passwords or API keys.'],
    ['title'=>'Wrong output or weak answer', 'text'=>'Send the input, the selected card, and what you expected Bringora to understand.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Support Center</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.item{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.item p{font-size:16px;line-height:1.55;color:#334155}.box{background:#f8fafc;border:1px solid #cbd5e1;border-radius:14px;padding:16px;white-space:pre-wrap}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Support Center</h1>
<p class="small">Simple beta support instructions so problems can be reported without exposing secrets.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="status.php">Status</a><a href="faq.php">FAQ</a><a href="landing.php">Landing</a></div>
</section>
<section class="card">
<h2>What to send when reporting a bug</h2>
<div class="box">Page:
Browser/device:
Exact visible error:
What you clicked:
Screenshot:

Do not send passwords, API keys, database credentials, or private_config.php.</div>
</section>
<?php foreach ($items as $item): ?>
<section class="item">
<h2><?php echo h($item['title']); ?></h2>
<p><?php echo h($item['text']); ?></p>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
