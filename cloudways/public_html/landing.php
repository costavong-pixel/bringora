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
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora - From Messy Thoughts to Clear Action</title>
<meta name="description" content="Bringora turns messy thoughts, notes, replies, decisions, and ideas into clear structured action.">
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0}.wrap{max-width:1120px;margin:auto;padding:28px}.hero{display:grid;grid-template-columns:1.1fr .9fr;gap:24px;align-items:center;padding:34px 0 52px}.card,.panel{background:#fff;border:1px solid #dbe3ef;border-radius:22px;box-shadow:0 8px 30px rgba(0,0,0,.08);padding:28px}.eyebrow{display:inline-block;background:#eef2ff;color:#3730a3;border-radius:999px;padding:8px 12px;font-weight:bold;font-size:13px}.h1{font-size:52px;line-height:1.02;margin:18px 0 14px;letter-spacing:-1.4px}.sub{font-size:20px;line-height:1.5;color:#475569}.cta{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}.btn{display:inline-block;border-radius:12px;padding:14px 18px;font-weight:bold;text-decoration:none}.primary{background:#2563eb;color:#fff}.secondary{background:#111827;color:#fff}.ghost{background:#fff;color:#111827;border:1px solid #cbd5e1}.grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:18px}.grid2{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:18px}.item{background:#fff;border:1px solid #dbe3ef;border-radius:18px;padding:20px}.item h3{margin:0 0 8px}.small{font-size:14px;color:#64748b;line-height:1.5}.section{padding:28px 0}.quote{background:#111827;color:#fff;border-radius:22px;padding:28px}.quote p{font-size:24px;line-height:1.35;margin:0}.status{font-size:13px;color:#64748b;margin-top:10px}.badges{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}.badge{background:#f8fafc;border:1px solid #cbd5e1;border-radius:999px;padding:8px 12px;font-weight:bold;font-size:13px}.demoBox{background:#f8fafc;border:1px solid #cbd5e1;border-radius:16px;padding:16px;white-space:pre-wrap}.footer{padding:28px 0;color:#64748b}@media(max-width:860px){.wrap{padding:18px}.hero{grid-template-columns:1fr;padding:20px 0 28px}.h1{font-size:38px}.grid3,.grid2{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="hero">
<div>
<span class="eyebrow">Private beta · AppSumo-ready direction</span>
<h1 class="h1">From messy thoughts to clear action.</h1>
<p class="sub">Bringora helps everyday users turn scattered notes, confusing decisions, rough replies, and half-formed ideas into organized output with one practical next step.</p>
<div class="cta">
<a class="btn primary" href="index.php">Try Bringora</a>
<a class="btn secondary" href="brain.php">Build My Bringora Brain</a>
<a class="btn ghost" href="examples.php">View Examples</a>
</div>
<p class="status">Website status: <?php echo h($status['website_ready'] ?? false ? 'ready' : 'pending'); ?> · Generate/API: <?php echo h($status['api_smoke_status'] ?? 'checking'); ?></p>
</div>
<div class="card">
<h2>What Bringora does</h2>
<div class="demoBox">Messy thought:
"need groceries cheap, kids lunch, dinner tonight, budget tight, no energy to think"

Bringora output:
- Organizes the problem
- Breaks it into categories
- Gives action steps
- Picks the next best action</div>
<div class="badges"><span class="badge">Daily planning</span><span class="badge">Replies</span><span class="badge">Decisions</span><span class="badge">Ideas</span><span class="badge">Notes</span></div>
</div>
</section>
<section class="section">
<h2>Built for people who think messy, not people who write perfect prompts.</h2>
<div class="grid3">
<div class="item"><h3>1. Clean up thoughts</h3><p class="small">Turn scattered notes into a structured answer, plan, or summary.</p></div>
<div class="item"><h3>2. Understand intent</h3><p class="small">Bringora should avoid dumb literal answers and infer the real-world action behind the question.</p></div>
<div class="item"><h3>3. Give the next step</h3><p class="small">Every output should end with one simple action the user can do next.</p></div>
</div>
</section>
<section class="section">
<div class="quote"><p>“Not another prompt tool. A small thinking assistant for normal daily mess.”</p></div>
</section>
<section class="section">
<h2>Five beta use cases</h2>
<div class="grid2">
<div class="item"><h3>Help me write or reply</h3><p class="small">Messages, complaints, emails, customer replies, better English.</p></div>
<div class="item"><h3>Help me decide</h3><p class="small">Compare choices, risks, costs, tradeoffs, and one next action.</p></div>
<div class="item"><h3>Organize my notes</h3><p class="small">Groceries, school, family, tasks, bills, ideas, messy reminders.</p></div>
<div class="item"><h3>Help me promote or sell</h3><p class="small">Ads, offers, local posts, product descriptions, simple campaign copy.</p></div>
<div class="item"><h3>Turn my idea into something</h3><p class="small">Creative ideas, brand concepts, story starts, product framing.</p></div>
<div class="item"><h3>My Bringora Brain</h3><p class="small">A browser-local profile for style, habits, routine, constraints, and projects.</p></div>
</div>
</section>
<section class="section panel">
<h2>Beta positioning</h2>
<p class="sub">Bringora is currently a website-first beta. The goal is to validate the core promise before adding heavier account, mobile app, or team features.</p>
<div class="cta"><a class="btn primary" href="index.php">Open Beta App</a><a class="btn ghost" href="status.php">Check Deploy Status</a></div>
</section>
<footer class="footer">Bringora · From messy thoughts to clear action.</footer>
</div>
</body>
</html>
