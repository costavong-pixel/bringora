<?php
require_once __DIR__ . '/../private_html/session.php';
bringora_start_session();

$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}

$config = require $configPath;
require_once __DIR__ . '/../private_html/db.php';
$betaPassword = $config['BETA_PASSWORD'] ?? '';
$appSumoCodes = is_array($config['APPSUMO_CODES'] ?? null) ? $config['APPSUMO_CODES'] : [];
if (empty($_SESSION['bringora_csrf_token'])) {
    $_SESSION['bringora_csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['bringora_csrf_token'];
$dailyLimit = (int)($_SESSION['bringora_daily_limit'] ?? ($config['DAILY_REQUEST_LIMIT'] ?? 25));
$usedToday = (int)($_SESSION['bringora_daily_count'] ?? 0);

if (isset($_GET['logout'])) {
    bringora_destroy_session();
    header('Location: index.php');
    exit;
}

if (isset($_POST['beta_password'])) {
    $submittedAccess = trim((string)$_POST['beta_password']);
    $submittedCode = strtoupper(preg_replace('/\s+/', '', $submittedAccess));

    if ($betaPassword !== '' && hash_equals($betaPassword, $submittedAccess)) {
        session_regenerate_id(true);
        $_SESSION['bringora_logged_in'] = true;
        $_SESSION['bringora_access_type'] = 'beta';
        $_SESSION['bringora_daily_limit'] = (int)($config['DAILY_REQUEST_LIMIT'] ?? 25);
        $_SESSION['bringora_monthly_limit'] = (int)($config['MONTHLY_REQUEST_LIMIT'] ?? 500);
        $_SESSION['bringora_csrf_token'] = bin2hex(random_bytes(32));
        header('Location: index.php');
        exit;
    }

    $codeConfig = null;
    if ($submittedCode !== '') {
        try {
            $db = bringora_db($config);
            $stmt = $db->prepare('SELECT tier, daily_limit, monthly_limit FROM redemption_codes WHERE code_value = :code_value LIMIT 1');
            $stmt->execute(['code_value' => $submittedCode]);
            $codeConfig = $stmt->fetch() ?: null;
        } catch (Throwable $e) {
            $codeConfig = null;
        }
    }
    if ($codeConfig === null && $submittedCode !== '' && isset($appSumoCodes[$submittedCode])) {
        $codeConfig = is_array($appSumoCodes[$submittedCode]) ? $appSumoCodes[$submittedCode] : [];
    }
    if ($submittedCode !== '' && is_array($codeConfig)) {
        session_regenerate_id(true);
        $_SESSION['bringora_logged_in'] = true;
        $_SESSION['bringora_access_type'] = 'appsumo';
        $_SESSION['bringora_appsumo_code'] = $submittedCode;
        $_SESSION['bringora_appsumo_tier'] = (string)($codeConfig['tier'] ?? 'tier1');
        $_SESSION['bringora_daily_limit'] = (int)($codeConfig['daily_limit'] ?? ($config['DAILY_REQUEST_LIMIT'] ?? 25));
        $_SESSION['bringora_monthly_limit'] = (int)($codeConfig['monthly_limit'] ?? ($config['MONTHLY_REQUEST_LIMIT'] ?? 500));
        $_SESSION['bringora_csrf_token'] = bin2hex(random_bytes(32));
        header('Location: index.php');
        exit;
    }

    $loginError = 'Wrong password or AppSumo code.';
}

if (empty($_SESSION['bringora_logged_in'])):
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Beta</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:30px}.card{max-width:430px;margin:90px auto;background:#fff;padding:28px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.08)}input{width:100%;padding:14px;border:1px solid #cbd5e1;border-radius:10px;box-sizing:border-box;font-size:16px}.row{display:flex;gap:8px}.row input{flex:1}.eye{width:72px;background:#111827;color:#fff;border:0;border-radius:10px}.enter{width:100%;margin-top:16px;padding:14px;background:#2563eb;color:#fff;border:0;border-radius:10px;font-weight:bold}.error{color:#b91c1c;font-weight:bold}.small{font-size:13px;color:#64748b;line-height:1.4}
</style>
</head>
<body>
<div class="card">
<h1>Bringora</h1>
<p>Handy Ora, always with you.</p>
<p class="small">Use your private beta password or an AppSumo redemption code.</p>
<form method="post">
<div class="row"><input id="pw" type="password" name="beta_password" placeholder="Enter beta password or AppSumo code" required><button class="eye" type="button" onclick="togglePw()">Show</button></div>
<button class="enter" type="submit">Enter</button>
</form>
<?php if (!empty($loginError)): ?><p class="error"><?php echo htmlspecialchars($loginError); ?></p><?php endif; ?>
</div>
<script>function togglePw(){const p=document.getElementById('pw');const b=document.querySelector('.eye');if(p.type==='password'){p.type='text';b.textContent='Hide'}else{p.type='password';b.textContent='Show'}}</script>
</body>
</html>
<?php exit; endif; ?>
<?php
try {
    $db = bringora_db($config);
    $usageCounts = bringora_period_counts($db, bringora_access_key($config));
    $usedToday = $usageCounts['daily'];
} catch (Throwable $e) {
    $usedToday = (int)($_SESSION['bringora_daily_count'] ?? 0);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:30px}.wrap{max-width:1060px;margin:auto}.card{background:#fff;padding:28px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.08)}.small{font-size:13px;color:#64748b}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}.choice{background:#f8fafc;color:#111827;text-align:left;border:1px solid #cbd5e1;border-radius:14px;padding:16px;min-height:105px;cursor:pointer}.choice.selected{background:#dbeafe;border-color:#2563eb}.title{display:block;font-weight:bold;font-size:17px;margin-bottom:8px}.desc{font-size:13px;color:#4b5563;line-height:1.4}textarea{width:100%;min-height:220px;padding:14px;border:1px solid #cbd5e1;border-radius:10px;box-sizing:border-box;font-size:16px;margin-top:10px}.primary{margin-top:18px;background:#2563eb;color:#fff;border:0;padding:14px 22px;border-radius:10px;font-weight:bold}.secondary{margin-left:8px;background:#111827;color:#fff;border:0;padding:14px 22px;border-radius:10px;font-weight:bold}.result{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;padding:18px;min-height:120px}.error{color:#b91c1c;font-weight:bold}.success{color:#166534;font-weight:bold}.topbar{display:flex;justify-content:space-between;gap:12px;align-items:center}.links a{margin-left:10px}.limit{background:#eef2ff;color:#3730a3;border-radius:999px;padding:6px 10px;font-weight:bold}@media(max-width:850px){.grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:560px){.grid{grid-template-columns:1fr}body{padding:16px}.secondary{display:block;margin-left:0}}
</style>
</head>
<body>
<div class="wrap"><div class="card">
<div class="topbar"><div><h1>Bringora</h1><p class="small">Private beta mode. Handy Ora, always with you.</p></div><div class="links small"><span class="limit"><?php echo htmlspecialchars((string)($_SESSION['bringora_appsumo_tier'] ?? 'beta')); ?></span><span id="usage" class="limit"><?php echo $usedToday; ?>/<?php echo $dailyLimit; ?> today</span><a href="saved_outputs.php">Saved Outputs</a><a href="privacy.php">Privacy</a><a href="terms.php">Terms</a><a href="support.php">Support</a><a href="?logout=1">Logout</a></div></div>
<p>Paste your messy thought. Bringora turns it into one clear structured output and a next best action.</p>
<h2>What are you struggling with today?</h2>
<input type="hidden" id="mode" value="write">
<div class="grid">
<button class="choice selected" type="button" onclick="selectMode('write',this)"><span class="title">Help Me Write or Reply</span><span class="desc">Messages, emails, replies, complaints, better English.</span></button>
<button class="choice" type="button" onclick="selectMode('understand',this)"><span class="title">Help Me Understand</span><span class="desc">Explain, summarize, simplify, study help.</span></button>
<button class="choice" type="button" onclick="selectMode('decision',this)"><span class="title">Help Me Decide</span><span class="desc">Compare choices, risk, cost, best next step.</span></button>
<button class="choice" type="button" onclick="selectMode('plan',this)"><span class="title">Make a Step-by-Step Plan</span><span class="desc">Turn a confusing goal into stages and actions.</span></button>
<button class="choice" type="button" onclick="selectMode('business',this)"><span class="title">Help Me Promote or Sell</span><span class="desc">Ads, offers, social posts, product descriptions.</span></button>
<button class="choice" type="button" onclick="selectMode('create',this)"><span class="title">Turn My Idea Into Something</span><span class="desc">Creative ideas, stories, visuals, brand concepts.</span></button>
<button class="choice" type="button" onclick="selectMode('organize_notes',this)"><span class="title">Organize My Notes</span><span class="desc">Groceries, school, family, tasks, bills, ideas.</span></button>
</div>
<label><strong>Paste your rough thought</strong></label>
<textarea id="promptText" placeholder="Example: need groceries cheap, kids school supplies, dinner idea, budget is tight..."></textarea>
<button class="primary" onclick="runBringora()">Generate My Strategy</button>
<button class="secondary" onclick="copyResult()">Copy Result</button>
<button class="secondary" onclick="saveResult()">Save Output</button>
<p class="small">Privacy MVP: prompt history is not saved.</p>
<div id="status" class="small"></div>
<h3>Your Bringora output</h3>
<div id="result" class="result"></div>
</div></div>
<script>
function selectMode(mode,btn){document.getElementById('mode').value=mode;document.querySelectorAll('.choice').forEach(c=>c.classList.remove('selected'));btn.classList.add('selected')}
async function runBringora(){const prompt=document.getElementById('promptText').value.trim();const mode=document.getElementById('mode').value;const status=document.getElementById('status');const result=document.getElementById('result');result.textContent='';status.textContent='';if(!prompt){status.innerHTML='<span class="error">Please paste your rough thought first.</span>';return}status.textContent='Thinking...';try{const response=await fetch('api.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-Bringora-CSRF':'<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>'},body:JSON.stringify({prompt,mode})});const data=await response.json();if(!response.ok||!data.success){status.innerHTML='<span class="error">'+(data.error||'Something went wrong.')+'</span>';return}result.textContent=data.result;if(data.usage){document.getElementById('usage').textContent=data.usage.used_today+'/'+data.usage.daily_limit+' today'}status.innerHTML='<span class="success">Done.</span>'}catch(e){status.innerHTML='<span class="error">Connection error. Please try again.</span>'}}
function copyResult(){const r=document.getElementById('result').textContent;if(!r.trim()){alert('No result to copy yet.');return}navigator.clipboard.writeText(r).then(()=>alert('Copied.'))}
async function saveResult(){const output=document.getElementById('result').textContent.trim();const mode=document.getElementById('mode').value;const status=document.getElementById('status');if(!output){alert('No result to save yet.');return}status.textContent='Saving...';try{const response=await fetch('save_output.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-Bringora-CSRF':'<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>'},body:JSON.stringify({output,mode})});const data=await response.json();if(!response.ok||!data.success){status.innerHTML='<span class="error">'+(data.error||'Could not save output.')+'</span>';return}status.innerHTML='<span class="success">Saved. <a href="saved_outputs.php">View saved outputs</a>.</span>'}catch(e){status.innerHTML='<span class="error">Connection error. Please try again.</span>'}}
</script>
</body>
</html>