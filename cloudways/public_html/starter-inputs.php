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
    ['name'=>'Decision clarity', 'card'=>'Help Me Decide', 'text'=>'I need to decide between option A and option B. My limits are time, cost, energy, and risk. Help me choose the next practical step.'],
    ['name'=>'Reply cleanup', 'card'=>'Help Me Write or Reply', 'text'=>'I need to reply to this message clearly and calmly. My goal is to solve the issue without sounding rude.'],
    ['name'=>'Daily notes', 'card'=>'Organize My Notes', 'text'=>'Here are my rough notes for today. Organize them into categories, action steps, and one next best action.'],
    ['name'=>'Small promotion', 'card'=>'Help Me Promote or Sell', 'text'=>'I have an offer for a local customer. Make it short, clear, and action-focused for a social post.'],
    ['name'=>'Idea to MVP', 'card'=>'Turn My Idea Into Something', 'text'=>'My idea is rough. Help me find the smallest useful version, what to skip, and what to build first.'],
    ['name'=>'Simple explanation', 'card'=>'Help Me Understand', 'text'=>'Explain this in simple words and tell me what I should do with the information next.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Starter Inputs</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:1100px;margin:auto}.card,.item{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}.small{font-size:14px;color:#64748b;line-height:1.5}.tag{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.box{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:14px;color:#334155}.btn{display:inline-block;background:#2563eb;color:#fff;text-decoration:none;border-radius:12px;padding:12px 15px;font-weight:bold}@media(max-width:850px){body{padding:16px}.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Starter Inputs</h1>
<p class="small">Simple starter inputs for users who do not know what to type first.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
<a class="btn" href="index.php">Open App</a>
</section>
<section class="grid">
<?php foreach ($items as $item): ?>
<article class="item">
<span class="tag"><?php echo h($item['card']); ?></span>
<h2><?php echo h($item['name']); ?></h2>
<div class="box"><?php echo h($item['text']); ?></div>
</article>
<?php endforeach; ?>
</section>
</main>
</body>
</html>
