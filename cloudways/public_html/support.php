<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
$config = file_exists($configPath) ? require $configPath : [];
$email = htmlspecialchars($config['SUPPORT_EMAIL'] ?? 'support@example.com', ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Support</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:26px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.btn{display:inline-block;background:#2563eb;color:#fff;text-decoration:none;border-radius:12px;padding:13px 16px;font-weight:bold}.box{background:#f8fafc;border:1px solid #cbd5e1;border-radius:14px;padding:16px;white-space:pre-wrap}@media(max-width:760px){body{padding:16px}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Support</h1>
<p class="small">Need help with Bringora beta access, usage, output quality, or a bug report?</p>
<p>Email: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
<p><a class="btn" href="support-center.php">Open Support Center</a></p>
</section>
<section class="card">
<h2>What to include</h2>
<div class="box">Page:
Browser/device:
Exact visible error:
What you clicked:
What you expected:
Screenshot if useful:</div>
<p class="small">Do not send passwords, API keys, database credentials, payment details, or private_config.php.</p>
</section>
</main>
</body>
</html>
