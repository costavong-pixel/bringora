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
$rows = [
    ['tool'=>'ChatGPT-style blank chat', 'difference'=>'Powerful, but users still need to know what to ask and how to explain context. Bringora starts with daily-life cards and pushes toward next action.'],
    ['tool'=>'Prompt optimizer', 'difference'=>'Optimizes wording for another AI. Bringora is positioned as a thinking companion that organizes the actual situation.'],
    ['tool'=>'Notion-style notes', 'difference'=>'Stores and organizes pages. Bringora is for taking rough thoughts and producing an actionable output fast.'],
    ['tool'=>'Todo app', 'difference'=>'Tracks tasks after you know what they are. Bringora helps decide what the task should be.'],
    ['tool'=>'Personal CRM', 'difference'=>'Manages contacts and history. Bringora focuses on personal context, preferences, constraints, and messy decisions.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Comparison</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.row{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.row{display:grid;grid-template-columns:260px 1fr;gap:18px}.tool{font-weight:800}.difference{color:#334155;line-height:1.55}.quote{background:#111827;color:#fff;border-radius:18px;padding:24px;margin-bottom:18px}.quote p{font-size:24px;line-height:1.35;margin:0}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top,.row{display:block}.links a{display:block;margin:8px 0}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>How Bringora Is Different</h1>
<p class="small">Bringora is not trying to be a blank AI chat, a prompt optimizer, or a notes database. It is positioned as a daily thinking companion.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="pricing.php">Pricing</a><a href="status.php">Status</a></div>
</section>
<section class="quote"><p>Bringora should help users who do not know how to explain the problem yet.</p></section>
<?php foreach ($rows as $row): ?>
<section class="row">
<div class="tool"><?php echo h($row['tool']); ?></div>
<div class="difference"><?php echo h($row['difference']); ?></div>
</section>
<?php endforeach; ?>
<section class="card">
<h2>Simple positioning</h2>
<p class="difference">Chat asks. Prompt tools polish. Note apps store. Bringora organizes messy thinking into clear action.</p>
</section>
</main>
</body>
</html>
