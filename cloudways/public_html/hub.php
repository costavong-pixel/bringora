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
$sections = [
    'Core' => [
        ['App','index.php','Private beta app. Generate is parked if API issue continues.'],
        ['Status','status.php','One-page deploy readiness check.'],
        ['My Bringora Brain','brain.php','Browser-local profile for style, habits, routine, constraints, and projects.'],
        ['Examples','examples.php','Sample inputs for beta testing.']
    ],
    'Public pages' => [
        ['Landing','landing.php','Sales positioning and product promise.'],
        ['Pricing','pricing.php','Draft beta and AppSumo tier positioning.'],
        ['FAQ','faq.php','Questions and answers for beta users.'],
        ['Compare','compare.php','How Bringora differs from chat, prompt tools, notes, and todo apps.']
    ],
    'Launch package' => [
        ['Use Cases','use-cases.php','Target markets and concrete situations.'],
        ['Demo Script','demo-script.php','Simple walkthrough script.'],
        ['Roadmap','roadmap.php','V1 to V5 product direction.'],
        ['Checklist','checklist.php','Launch readiness checklist.'],
        ['Support Center','support-center.php','Bug-report and support instructions.'],
        ['Changelog','changelog.php','Visible history of what changed and what is parked.']
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Hub</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:1100px;margin:auto}.card,.group,.link{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef}.top{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.group{margin-bottom:18px}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.link{text-decoration:none;color:#111827;display:block}.link h3{margin:0 0 8px}.link p{margin:0;color:#64748b;line-height:1.45}.status{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:7px 11px;font-weight:bold;font-size:13px}.quick a{margin-right:12px}@media(max-width:800px){body{padding:16px}.top{display:block}.grid{grid-template-columns:1fr}.quick a{display:block;margin:8px 0}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>Bringora Hub</h1>
<p class="small">One central place to open every Bringora beta, product, launch, and support page.</p>
<p><span class="status"><?php echo h($status['website_ready'] ?? false ? 'Website ready' : 'Deploy pending or unknown'); ?></span></p>
</div>
<div class="quick"><a href="landing.php">Landing</a><a href="status.php">Status</a><a href="index.php">App</a></div>
</section>
<?php foreach ($sections as $title => $links): ?>
<section class="group">
<h2><?php echo h($title); ?></h2>
<div class="grid">
<?php foreach ($links as $link): ?>
<a class="link" href="<?php echo h($link[1]); ?>">
<h3><?php echo h($link[0]); ?></h3>
<p><?php echo h($link[2]); ?></p>
</a>
<?php endforeach; ?>
</div>
</section>
<?php endforeach; ?>
</main>
</body>
</html>
