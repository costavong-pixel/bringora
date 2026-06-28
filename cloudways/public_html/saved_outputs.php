<?php
session_start();

$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}

$config = require $configPath;
require_once __DIR__ . '/../private_html/db.php';

if (empty($_SESSION['bringora_logged_in'])) {
    header('Location: index.php');
    exit;
}
if (empty($_SESSION['bringora_csrf_token'])) {
    $_SESSION['bringora_csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['bringora_csrf_token'];
$accessKey = bringora_access_key($config);
$error = '';

try {
    $db = bringora_db($config);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
        $actualToken = $_POST['csrf_token'] ?? '';
        if (!hash_equals($csrfToken, $actualToken)) {
            $error = 'Security check failed. Refresh the page and try again.';
        } else {
            $delete = $db->prepare('DELETE FROM saved_outputs WHERE id = :id AND access_key = :access_key');
            $delete->execute([
                'id' => (int)($_POST['id'] ?? 0),
                'access_key' => $accessKey,
            ]);
            header('Location: saved_outputs.php');
            exit;
        }
    }

    $stmt = $db->prepare('SELECT id, category, title, output_text, created_at FROM saved_outputs WHERE access_key = :access_key ORDER BY created_at DESC, id DESC LIMIT 100');
    $stmt->execute(['access_key' => $accessKey]);
    $outputs = $stmt->fetchAll();
} catch (Throwable $e) {
    $outputs = [];
    $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Saved Bringora Outputs</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:30px}.wrap{max-width:980px;margin:auto}.card,.item{background:#fff;padding:24px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.small{font-size:13px;color:#64748b}.output{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;padding:16px}.delete{background:#b91c1c;color:#fff;border:0;padding:10px 14px;border-radius:10px;font-weight:bold}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.error{color:#b91c1c;font-weight:bold}
</style>
</head>
<body>
<main class="wrap">
<section class="card top"><div><h1>Saved Outputs</h1><p class="small">Only generated outputs are saved here. Raw prompt input is not saved by default.</p></div><a href="index.php">Back to Bringora</a></section>
<?php if ($error !== ''): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<?php if (empty($outputs)): ?>
<section class="item"><p>No saved outputs yet.</p></section>
<?php endif; ?>
<?php foreach ($outputs as $output): ?>
<article class="item">
<h2><?php echo htmlspecialchars((string)($output['title'] ?: 'Saved output')); ?></h2>
<p class="small"><?php echo htmlspecialchars((string)$output['category']); ?> · <?php echo htmlspecialchars((string)$output['created_at']); ?> UTC</p>
<div class="output"><?php echo htmlspecialchars((string)$output['output_text']); ?></div>
<form method="post" onsubmit="return confirm('Delete this saved output?');">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?php echo (int)$output['id']; ?>">
<p><button class="delete" type="submit">Delete saved output</button></p>
</form>
</article>
<?php endforeach; ?>
</main>
</body>
</html>
