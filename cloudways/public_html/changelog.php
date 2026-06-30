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
$changes = [
    ['date'=>'Current beta','title'=>'Website product foundation','items'=>['Landing page','Pricing draft','FAQ','Roadmap','Use cases','Comparison page','Demo script','Launch checklist','Support center','Status page']],
    ['date'=>'Current beta','title'=>'My Bringora Brain','items'=>['Browser-local profile page','Style, habits, routine, priorities, constraints, and projects','Copyable profile summary']],
    ['date'=>'Parked','title'=>'Generate/API issue','items'=>['Backend diagnostics added','API smoke test changed to diagnostic only','Website deploy no longer blocked by Generate issue']],
    ['date'=>'Next','title'=>'Launch-readiness polish','items'=>['Better navigation between public pages','Stronger AppSumo copy','Support wording cleanup','Reconnect profile into generation after API issue is fixed']]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Changelog</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.change{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.date{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.change ul{margin:12px 0 0 22px;padding:0}.change li{margin:8px 0}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Changelog</h1>
<p class="small">A simple visible history of what has been added and what is parked.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="status.php">Status</a><a href="checklist.php">Checklist</a><a href="landing.php">Landing</a></div>
</section>
<?php foreach ($changes as $change): ?>
<section class="change">
<span class="date"><?php echo h($change['date']); ?></span>
<h2><?php echo h($change['title']); ?></h2>
<ul>
<?php foreach ($change['items'] as $item): ?>
<li><?php echo h($item); ?></li>
<?php endforeach; ?>
</ul>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
