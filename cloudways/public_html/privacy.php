<?php http_response_code(200); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Privacy</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:26px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}li{margin:10px 0;line-height:1.5}@media(max-width:760px){body{padding:16px}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Privacy</h1>
<p class="small">Bringora is a private beta text-only thinking companion.</p>
<ul>
<li>Your selected card and text input are sent to Bringora so the app can generate one structured output.</li>
<li>The MVP does not save raw prompts by default.</li>
<li>Generated outputs are saved only when you choose to save them.</li>
<li>My Bringora Brain is stored in your browser local storage for now.</li>
<li>If My Bringora Brain is filled out, the main app can send it as optional local context during generation.</li>
<li>AI processing happens through the server. Provider keys are not exposed in the browser.</li>
<li>Usage counts may be stored to enforce beta limits.</li>
</ul>
<p class="small">Do not submit passwords, payment details, private keys, or highly sensitive personal data.</p>
</section>
</main>
</body>
</html>
