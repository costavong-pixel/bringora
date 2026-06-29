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
$groups = [
    'Public website' => [
        'Landing page exists and explains the promise',
        'Pricing draft exists with beta wording',
        'FAQ explains what Bringora is and is not',
        'Use cases are clear for target users',
        'Comparison page explains difference from chat and prompt tools'
    ],
    'Beta product' => [
        'My Bringora Brain page exists',
        'Examples page exists for testing',
        'Onboarding page explains first use',
        'Roadmap page explains V1 to V5 direction',
        'Generate issue is tracked as parked, not blocking website pages'
    ],
    'Operations' => [
        'Status page tells whether website deploy is ready',
        'GitHub Actions deploys public and private code safely',
        'private_config.php is excluded from deploy overwrite',
        'Health endpoint exists',
        'API smoke test is diagnostic only while Generate is parked'
    ],
    'Before AppSumo' => [
        'Decide final LTD tiers',
        'Record short demo video',
        'Prepare support email and refund-safe beta wording',
        'Fix Generate browser issue',
        'Connect My Bringora Brain into generation flow'
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Launch Checklist</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.group{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.group ul{margin:12px 0 0 0;padding:0;list-style:none}.group li{margin:10px 0;padding-left:30px;position:relative}.group li:before{content:'□';position:absolute;left:0;color:#2563eb;font-weight:bold}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Launch Checklist</h1>
<p class="small">One page to track what is ready, what is parked, and what must be done before launch.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="status.php">Status</a><a href="roadmap.php">Roadmap</a></div>
</section>
<?php foreach ($groups as $title => $items): ?>
<section class="group">
<h2><?php echo h($title); ?></h2>
<ul>
<?php foreach ($items as $item): ?>
<li><?php echo h($item); ?></li>
<?php endforeach; ?>
</ul>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
