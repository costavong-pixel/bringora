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
$bullets = [
    'Turn messy thoughts into clear action steps',
    'Use simple cards instead of a blank prompt box',
    'Save personal context with My Bringora Brain',
    'Organize writing, decisions, notes, plans, and business ideas',
    'Designed as a lightweight website-first beta'
];
$assets = [
    ['name'=>'One-line pitch','value'=>'Bringora helps people turn messy thoughts into clear action.'],
    ['name'=>'Short pitch','value'=>'A context-aware thinking companion for users who do not know how to explain the problem yet.'],
    ['name'=>'Best-fit users','value'=>'Busy parents, students, small business owners, creators, seniors, and non-technical users.'],
    ['name'=>'Not positioned as','value'=>'Not a blank chat clone, not only a prompt optimizer, and not a notes database.'],
    ['name'=>'Beta limitation','value'=>'Generate/API can be parked while public website pages continue moving.']
];
$tiers = [
    ['tier'=>'Tier 1','fit'=>'Solo daily use','limit'=>'Moderate daily and monthly usage','notes'=>'Good for personal organization and simple outputs.'],
    ['tier'=>'Tier 2','fit'=>'Power users','limit'=>'Higher daily and monthly usage','notes'=>'Better for creators, business owners, and heavier beta feedback.'],
    ['tier'=>'Future plan','fit'=>'Teams or family use','limit'=>'To be decided','notes'=>'Only after the core product and API flow are stable.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora AppSumo Prep</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:1100px;margin:auto}.card,.panel{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.hero{font-size:44px;line-height:1.05;margin:8px 0}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}.asset{background:#f8fafc;border:1px solid #cbd5e1;border-radius:14px;padding:16px}.asset strong{display:block;margin-bottom:8px}.tier{display:grid;grid-template-columns:140px 1fr 1fr 1fr;gap:12px;border-top:1px solid #e2e8f0;padding:14px 0}.links a{margin-right:12px}.badge{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}ul{margin:12px 0 0 22px;padding:0}li{margin:8px 0}@media(max-width:850px){body{padding:16px}.top{display:block}.hero{font-size:34px}.grid{grid-template-columns:1fr}.tier{grid-template-columns:1fr}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<span class="badge">AppSumo prep</span>
<h1 class="hero">From messy thoughts to clear action.</h1>
<p class="small">A launch-prep page for positioning, feature bullets, beta limitations, and draft lifetime-deal tiers.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="hub.php">Hub</a><a href="pricing.php">Pricing</a><a href="checklist.php">Checklist</a></div>
</section>
<section class="panel">
<h2>Core sales bullets</h2>
<ul>
<?php foreach ($bullets as $bullet): ?>
<li><?php echo h($bullet); ?></li>
<?php endforeach; ?>
</ul>
</section>
<section class="panel">
<h2>Launch copy assets</h2>
<div class="grid">
<?php foreach ($assets as $asset): ?>
<div class="asset"><strong><?php echo h($asset['name']); ?></strong><?php echo h($asset['value']); ?></div>
<?php endforeach; ?>
</div>
</section>
<section class="panel">
<h2>Draft tier direction</h2>
<?php foreach ($tiers as $tier): ?>
<div class="tier">
<strong><?php echo h($tier['tier']); ?></strong>
<div><?php echo h($tier['fit']); ?></div>
<div><?php echo h($tier['limit']); ?></div>
<div><?php echo h($tier['notes']); ?></div>
</div>
<?php endforeach; ?>
</section>
</main>
</body>
</html>
