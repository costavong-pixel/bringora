<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}
$examples = [
    ['title'=>'Car wash intent test','card'=>'Help Me Decide','input'=>'How far is the car wash?','why'=>'Tests whether Bringora infers the practical action instead of only giving a literal distance.'],
    ['title'=>'Budget groceries','card'=>'Organize My Notes','input'=>'Need cheap groceries, kids lunch, dinner tonight, budget tight, no energy to think.','why'=>'Shows daily-life organization, constraints, and next action.'],
    ['title'=>'Complaint reply','card'=>'Help Me Write or Reply','input'=>'Customer says food was cold and wants refund but driver was late. Need reply not sound rude.','why'=>'Shows tone repair and practical response drafting.'],
    ['title'=>'Small business promo','card'=>'Help Me Promote or Sell','input'=>'Burger promo today only, 30 burgers, 50 percent off, Richmond location, need Google post.','why'=>'Shows local business marketing with urgency and limits.'],
    ['title'=>'Messy idea to product','card'=>'Turn My Idea Into Something','input'=>'I want app that remembers how I think and turns messy thoughts into clear action, maybe AppSumo.','why'=>'Shows product framing, feature shaping, and MVP thinking.'],
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Examples</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card,.example{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.tag{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:6px 10px;font-weight:bold;font-size:13px}.input{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;padding:14px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}@media(max-width:760px){body{padding:16px}.top{display:block}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top"><div><h1>Bringora Examples</h1><p class="small">Use these examples to test whether Bringora understands intent, context, constraints, and next action.</p></div><a href="index.php">Back to Bringora</a></section>
<?php foreach ($examples as $example): ?>
<article class="example">
<h2><?php echo htmlspecialchars($example['title']); ?></h2>
<p><span class="tag"><?php echo htmlspecialchars($example['card']); ?></span></p>
<div class="input"><?php echo htmlspecialchars($example['input']); ?></div>
<p class="small"><strong>Why it matters:</strong> <?php echo htmlspecialchars($example['why']); ?></p>
</article>
<?php endforeach; ?>
</main>
</body>
</html>
