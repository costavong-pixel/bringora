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
$useCases = [
    ['audience'=>'Busy parents', 'pain'=>'Too many small tasks and scattered notes.', 'bringora'=>'Turn groceries, school needs, dinner ideas, and reminders into one practical plan.', 'sample'=>'Need cheap groceries, kids lunch, dinner tonight, budget tight.'],
    ['audience'=>'Students', 'pain'=>'Assignments, study notes, and unclear instructions pile up.', 'bringora'=>'Simplify notes, organize study steps, and create a next action.', 'sample'=>'I have exam notes but do not know what to study first.'],
    ['audience'=>'Small business owners', 'pain'=>'Need fast replies, promos, and decisions without agency-level setup.', 'bringora'=>'Draft replies, local posts, offer copy, and simple decision breakdowns.', 'sample'=>'Customer complained food was cold, need reply not rude.'],
    ['audience'=>'Seniors and non-technical users', 'pain'=>'AI tools feel too abstract and prompt-heavy.', 'bringora'=>'Use simple categories and plain-language output instead of requiring perfect prompts.', 'sample'=>'I need to write message to landlord but not sound angry.'],
    ['audience'=>'Creators and affiliates', 'pain'=>'Ideas are messy and hard to turn into content.', 'bringora'=>'Turn rough product ideas into post angles, scripts, captions, and next steps.', 'sample'=>'Skin care product affiliate, need short IG idea.'],
    ['audience'=>'People with limited energy', 'pain'=>'Even small decisions feel heavy when tired or stressed.', 'bringora'=>'Reduce the decision into a short plan and one next best action.', 'sample'=>'I feel tired but need plan for today.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Use Cases</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:1100px;margin:auto}.card,.case{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef}.top{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:18px}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}.small{font-size:14px;color:#64748b;line-height:1.5}.tag{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.sample{background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:13px;white-space:pre-wrap;color:#334155}.links a{margin-right:12px}@media(max-width:850px){body{padding:16px}.top{display:block}.grid{grid-template-columns:1fr}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Use Cases</h1>
<p class="small">Concrete markets and situations Bringora can serve during beta and AppSumo positioning.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="pricing.php">Pricing</a><a href="status.php">Status</a></div>
</section>
<section class="grid">
<?php foreach ($useCases as $item): ?>
<article class="case">
<span class="tag"><?php echo h($item['audience']); ?></span>
<h2><?php echo h($item['pain']); ?></h2>
<p><?php echo h($item['bringora']); ?></p>
<p class="small"><strong>Sample input</strong></p>
<div class="sample"><?php echo h($item['sample']); ?></div>
</article>
<?php endforeach; ?>
</section>
</main>
</body>
</html>
