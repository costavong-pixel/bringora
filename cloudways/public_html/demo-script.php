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
$steps = [
    ['title'=>'Open with the headache', 'text'=>'Most AI tools ask normal people to write perfect prompts. Bringora starts from messy thoughts instead.'],
    ['title'=>'Show the messy input', 'text'=>'Paste a rough situation like: Need cheap groceries, kids lunch, dinner tonight, budget tight, no energy to think.'],
    ['title'=>'Show structured output', 'text'=>'Bringora organizes the situation, breaks it into categories, gives action steps, and picks the next best action.'],
    ['title'=>'Show My Bringora Brain', 'text'=>'Open the profile page and show style, habits, routine, priorities, constraints, and projects saved locally.'],
    ['title'=>'Explain the beta', 'text'=>'This is a website-first beta. The goal is to validate the daily-thinking companion before overbuilding mobile apps or heavy accounts.'],
    ['title'=>'Close with the promise', 'text'=>'Bringora: from messy thoughts to clear action.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Demo Script</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.step{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.num{display:inline-block;background:#2563eb;color:#fff;border-radius:999px;padding:7px 11px;font-weight:bold;margin-right:8px}.script{font-size:18px;line-height:1.6;color:#334155}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card top">
<div>
<h1>Bringora Demo Script</h1>
<p class="small">A simple demo flow for AppSumo, beta users, and quick video walkthroughs.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="examples.php">Examples</a><a href="status.php">Status</a></div>
</section>
<?php foreach ($steps as $i => $step): ?>
<section class="step">
<h2><span class="num"><?php echo $i + 1; ?></span><?php echo h($step['title']); ?></h2>
<p class="script"><?php echo h($step['text']); ?></p>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
