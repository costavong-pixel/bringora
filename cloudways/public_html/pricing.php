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
$plans = [
    ['name'=>'Beta Starter','price'=>'Free beta','tag'=>'Testing','best'=>false,'features'=>['Private beta access','Core Bringora cards','Browser-local My Bringora Brain','Examples and roadmap','Daily beta usage limit']],
    ['name'=>'AppSumo Tier 1','price'=>'TBD LTD','tag'=>'Solo users','best'=>true,'features'=>['Higher daily usage limit','Saved outputs when stable','My Bringora Brain profile','Future profile export/import','Launch-period updates']],
    ['name'=>'AppSumo Tier 2','price'=>'TBD LTD','tag'=>'Power users','best'=>false,'features'=>['Larger monthly usage limit','More saved outputs','More context/profile space','Priority beta feedback','Future advanced templates']]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Pricing</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:1100px;margin:auto}.card,.plan{background:#fff;padding:24px;border-radius:20px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef}.top{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.plan{position:relative}.best{border:2px solid #2563eb}.badge{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.price{font-size:30px;font-weight:800;margin:14px 0}.plan ul{margin:18px 0 0 20px;padding:0}.plan li{margin:10px 0}.btn{display:inline-block;background:#2563eb;color:#fff;text-decoration:none;border-radius:12px;padding:13px 16px;font-weight:bold;margin-top:18px}.ghost{background:#111827}.note{margin-top:18px;background:#fffbeb;border:1px solid #fde68a;border-radius:16px;padding:16px}.links a{margin-right:12px}@media(max-width:850px){body{padding:16px}.top{display:block}.grid{grid-template-columns:1fr}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Pricing</h1>
<p class="small">Draft beta pricing structure for AppSumo-style positioning. Final prices are not locked yet.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="roadmap.php">Roadmap</a><a href="status.php">Status</a></div>
</section>
<section class="grid">
<?php foreach ($plans as $plan): ?>
<article class="plan <?php echo $plan['best'] ? 'best' : ''; ?>">
<span class="badge"><?php echo h($plan['tag']); ?></span>
<h2><?php echo h($plan['name']); ?></h2>
<div class="price"><?php echo h($plan['price']); ?></div>
<ul>
<?php foreach ($plan['features'] as $feature): ?>
<li><?php echo h($feature); ?></li>
<?php endforeach; ?>
</ul>
<a class="btn <?php echo $plan['best'] ? '' : 'ghost'; ?>" href="index.php">Open beta</a>
</article>
<?php endforeach; ?>
</section>
<section class="note">
<strong>Beta note:</strong> Generate/API can be parked while website pages continue. Pricing copy is for positioning and can be changed before launch.
</section>
</main>
</body>
</html>
