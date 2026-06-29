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
$faqs = [
    ['q'=>'What is Bringora?', 'a'=>'Bringora is a website-first thinking companion that turns messy thoughts into clear structured action.'],
    ['q'=>'Is Bringora just a prompt optimizer?', 'a'=>'No. The goal is not only to make better prompts. Bringora should understand the user intent, organize the situation, and suggest the next practical action.'],
    ['q'=>'What is My Bringora Brain?', 'a'=>'My Bringora Brain is an optional browser-local profile for your style, habits, routine, priorities, constraints, and projects.'],
    ['q'=>'Is my raw prompt history saved?', 'a'=>'The beta direction is privacy-light: raw prompt history is not saved by default. Saved outputs are only stored when the user chooses to save them.'],
    ['q'=>'Why does Generate say parked on the status page?', 'a'=>'The website pages can continue moving while the Generate/API issue is isolated. The product pages, examples, roadmap, pricing, and profile page do not depend on Generate.'],
    ['q'=>'Is this ready for AppSumo?', 'a'=>'Not yet. The current goal is to shape a credible AppSumo MVP package: landing page, pricing draft, examples, roadmap, support wording, and a working beta flow.'],
    ['q'=>'What should be built next?', 'a'=>'Finish the public product pages first, then reconnect My Bringora Brain into the generation flow, then fix saved outputs and AppSumo tier handling.']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora FAQ</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.faq{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:14px;color:#64748b;line-height:1.5}.faq h2{margin:0 0 10px}.faq p{font-size:16px;line-height:1.55;color:#475569}.links a{margin-right:12px}@media(max-width:760px){body{padding:16px}.top{display:block}.links a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora FAQ</h1>
<p class="small">Clear answers for beta users, AppSumo reviewers, and future customers.</p>
<p class="small">Deploy status: <?php echo h($status['website_ready'] ?? false ? 'website ready' : 'pending or unknown'); ?></p>
</div>
<div class="links"><a href="landing.php">Landing</a><a href="pricing.php">Pricing</a><a href="status.php">Status</a></div>
</section>
<?php foreach ($faqs as $faq): ?>
<section class="faq">
<h2><?php echo h($faq['q']); ?></h2>
<p><?php echo h($faq['a']); ?></p>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
