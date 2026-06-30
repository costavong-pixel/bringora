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
    ['title'=>'Start with one messy thought','text'=>'Do not try to write a perfect prompt. Paste the rough situation exactly as it is in your head.'],
    ['title'=>'Pick the closest card','text'=>'Choose writing, understanding, decision, planning, business, creation, or notes. The card gives Bringora direction.'],
    ['title'=>'Add your context','text'=>'Use My Bringora Brain to save your style, habits, routine, priorities, constraints, and projects in your browser.'],
    ['title'=>'Read the next best action','text'=>'The most important part is the final next action. Bringora should reduce the messy thought into one thing to do now.'],
    ['title'=>'Save or copy useful output','text'=>'Copy results you want to reuse. Saved outputs are a planned beta flow and may be limited while Generate is parked.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Onboarding</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.step{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.num{display:inline-block;background:#2563eb;color:#fff;border-radius:999px;padding:7px 11px;font-weight:bold;margin-right:8px}.step p{font-size:17px;line-height:1.55;color:#334155}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card top">
<div>
<h1>Bringora Onboarding</h1>
<p class="small">A simple first-use guide for beta users.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="brain.php">My Brain</a><a href="status.php">Status</a></div>
</section>
<?php foreach ($steps as $i => $step): ?>
<section class="step">
<h2><span class="num"><?php echo $i + 1; ?></span><?php echo h($step['title']); ?></h2>
<p><?php echo h($step['text']); ?></p>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
