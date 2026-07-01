<?php http_response_code(200); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Test Results</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.step{display:grid;grid-template-columns:44px 1fr;gap:14px;border-bottom:1px solid #e5e7eb;padding:16px 0}.step:last-child{border-bottom:0}.num{background:#2563eb;color:#fff;border-radius:999px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;font-weight:bold}.btn{display:inline-block;background:#2563eb;color:#fff;text-decoration:none;border-radius:12px;padding:10px 13px;font-weight:bold;margin-right:8px;margin-top:8px}.box{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:14px}.ok{color:#166534;font-weight:bold}.bad{color:#b91c1c;font-weight:bold}@media(max-width:760px){body{padding:16px}.step{grid-template-columns:1fr}.btn{display:block;margin-right:0}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Test Results Checklist</h1>
<p class="small">Use this page to record the exact result from each diagnostic page. This prevents guessing while fixing Generate/API.</p>
</section>
<section class="card">
<div class="step"><div class="num">1</div><div><h2>API Debug</h2><p class="small">Checks PHP, cURL, PDO, database, auth, and basic config.</p><a class="btn" href="api-debug.php">Open API Debug</a><div class="box">Result:
Pass / Fail:
Message:</div></div></div>
<div class="step"><div class="num">2</div><div><h2>Provider Test</h2><p class="small">Checks whether DeepSeek responds to one tiny request.</p><a class="btn" href="provider-test.php">Open Provider Test</a><div class="box">Result:
Pass / Fail:
HTTP code:
Message:</div></div></div>
<div class="step"><div class="num">3</div><div><h2>App API Test</h2><p class="small">Checks the full server-side api.php Generate pipeline.</p><a class="btn" href="app-api-test.php">Open App API Test</a><div class="box">Result:
Pass / Fail:
HTTP code:
Message:</div></div></div>
<div class="step"><div class="num">4</div><div><h2>Browser API Test</h2><p class="small">Checks browser fetch behavior using the same header/JSON path as the app.</p><a class="btn" href="browser-api-test.php">Open Browser API Test</a><div class="box">Result:
Pass / Fail:
HTTP code:
Message:</div></div></div>
<div class="step"><div class="num">5</div><div><h2>Main App Generate</h2><p class="small">Final user-facing Generate test.</p><a class="btn" href="index.php">Open App</a><div class="box">Result:
Pass / Fail:
Visible error:
Output looks correct:</div></div></div>
</section>
<section class="card">
<h2>Interpretation</h2>
<p class="small"><span class="bad">First failing step is the blocker.</span> Fix that layer before testing later layers.</p>
<p class="small"><span class="ok">If all tests pass but output quality is weak,</span> the blocker is not infrastructure. Improve the system prompt, cards, examples, and My Brain usage.</p>
</section>
</main>
</body>
</html>
