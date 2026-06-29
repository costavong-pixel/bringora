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
$stages = [
    ['phase'=>'V1', 'title'=>'Website beta', 'state'=>'In progress', 'items'=>['Beta access flow','Core Bringora cards','Examples page','My Bringora Brain local profile','Deployment status page']],
    ['phase'=>'V2', 'title'=>'Context-aware output', 'state'=>'Next', 'items'=>['Use My Bringora Brain in generation','Better intent detection tests','Hidden-intent benchmark','Cleaner mobile result display']],
    ['phase'=>'V3', 'title'=>'Saved outputs and profiles', 'state'=>'Planned', 'items'=>['Save generated outputs reliably','Profile export and import','Simple account layer','Usage-limit handling']],
    ['phase'=>'V4', 'title'=>'Launch package', 'state'=>'Planned', 'items'=>['Landing page polish','Demo script','FAQ and support copy','Tier comparison','Clear beta wording']],
    ['phase'=>'V5', 'title'=>'Assistant expansion', 'state'=>'Later', 'items'=>['Browser memory improvements','Project-specific brains','Template library','Optional stronger-model fallback']]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Roadmap</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.phase{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.phaseHead{display:flex;justify-content:space-between;gap:12px;align-items:center}.badge{background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.state{background:#f8fafc;border:1px solid #cbd5e1;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.phase ul{margin:12px 0 0 22px;padding:0}.phase li{margin:8px 0}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top,.phaseHead{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Product Roadmap</h1>
<p class="small">A practical roadmap for getting Bringora from private beta to launch-ready MVP.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="index.php">App</a><a href="status.php">Status</a></div>
</section>
<?php foreach ($stages as $stage): ?>
<section class="phase">
<div class="phaseHead"><h2><span class="badge"><?php echo h($stage['phase']); ?></span> <?php echo h($stage['title']); ?></h2><span class="state"><?php echo h($stage['state']); ?></span></div>
<ul>
<?php foreach ($stage['items'] as $item): ?>
<li><?php echo h($item); ?></li>
<?php endforeach; ?>
</ul>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
