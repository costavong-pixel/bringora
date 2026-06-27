<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
$config = file_exists($configPath) ? require $configPath : [];
$email = htmlspecialchars($config['SUPPORT_EMAIL'] ?? 'support@example.com', ENT_QUOTES);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Bringora Support</title><style>body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:30px}.card{max-width:760px;margin:auto;background:#fff;padding:28px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.08)}</style></head><body><main class="card"><h1>Support</h1><p>Need help with Bringora beta access, usage, or output quality?</p><p>Email: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p><p>Please include what card you selected and what you expected Bringora to help organize. Do not send passwords or API keys.</p><p><a href="index.php">Back to Bringora</a></p></main></body></html>
