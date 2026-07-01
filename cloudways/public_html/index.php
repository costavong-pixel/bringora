<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}

$config = require $configPath;
require_once __DIR__ . '/../private_html/auth.php';
require_once __DIR__ . '/../private_html/db.php';

$betaPassword = (string)($config['BETA_PASSWORD'] ?? '');
$accessToken = bringora_beta_access_token($config);
$appSumoCodes = is_array($config['APPSUMO_CODES'] ?? null) ? $config['APPSUMO_CODES'] : [];
$loginError = '';

if (isset($_GET['logout'])) {
    bringora_clear_auth_cookie();
    header('Location: index.php');
    exit;
}

if (isset($_POST['beta_password'])) {
    $submittedAccess = trim((string)$_POST['beta_password']);
    $submittedCode = strtoupper(preg_replace('/\s+/', '', $submittedAccess));

    if ($betaPassword !== '' && hash_equals($betaPassword, $submittedAccess)) {
        $token = bringora_sign_auth_payload([
            'type' => 'beta',
            'tier' => 'beta',
            'daily_limit' => (int)($config['DAILY_REQUEST_LIMIT'] ?? 25),
            'monthly_limit' => (int)($config['MONTHLY_REQUEST_LIMIT'] ?? 500),
        ], $config);
        if ($token !== '') {
            bringora_set_auth_cookie($token);
        }
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
        $token = bringora_sign_auth_payload([
            'type' => 'appsumo',
            'code' => $submittedCode,
            'tier' => (string)($codeConfig['tier'] ?? 'tier1'),
            'daily_limit' => (int)($codeConfig['daily_limit'] ?? ($config['DAILY_REQUEST_LIMIT'] ?? 25)),
            'monthly_limit' => (int)($codeConfig['monthly_limit'] ?? ($config['MONTHLY_REQUEST_LIMIT'] ?? 500)),
        ], $config);
        if ($token !== '') {
            bringora_set_auth_cookie($token);
        }
        header('Location: index.php');
        exit;
    }

    $loginError = 'Wrong password or AppSumo code.';
}

$authPayload = bringora_read_auth_payload($config);
if ($authPayload === null):
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
<p>From messy thoughts to clear action.</p>
<p class="small">Use your private beta password or an AppSumo redemption code.</p>
<form method="post">
<div class="row"><input id="pw" type="password" name="beta_password" placeholder="Enter beta password or AppSumo code" required><button class="eye" type="button" onclick="togglePw()">Show</button></div>
<button class="enter" type="submit">Enter</button>
</form>
<?php if ($loginError !== ''): ?><p class="error"><?php echo htmlspecialchars($loginError); ?></p><?php endif; ?>
</div>
<script>function togglePw(){const p=document.getElementById('pw');const b=document.querySelector('.eye');if(p.type==='password'){p.type='text';b.textContent='Hide'}else{p.type='password';b.textContent='Show'}}</script>
</body>
</html>
<?php exit; endif; ?>
<?php
bringora_apply_auth_payload($authPayload);
$dailyLimit = bringora_daily_limit($config);
$usedToday = 0;
try {
    $db = bringora_db($config);
    $usageCounts = bringora_period_counts($db, bringora_access_key($config));
    $usedToday = $usageCounts['daily'];
} catch (Throwable $e) {
    $usedToday = 0;
}
$tier = (string)($authPayload['tier'] ?? $authPayload['type'] ?? 'beta');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:30px}.wrap{max-width:1060px;margin:auto}.card{background:#fff;padding:28px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.08)}.small{font-size:13px;color:#64748b}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}.choice{background:#f8fafc;color:#111827;text-align:left;border:1px solid #cbd5e1;border-radius:14px;padding:16px;min-height:105px;cursor:pointer}.choice.selected{background:#dbeafe;border-color:#2563eb}.title{display:block;font-weight:bold;font-size:17px;margin-bottom:8px}.desc{font-size:13px;color:#4b5563;line-height:1.4}textarea{width:100%;min-height:220px;padding:14px;border:1px solid #cbd5e1;border-radius:10px;box-sizing:border-box;font-size:16px;margin-top:10px}.primary{margin-top:18px;background:#2563eb;color:#fff;border:0;padding:14px 22px;border-radius:10px;font-weight:bold}.secondary{margin-left:8px;background:#111827;color:#fff;border:0;padding:14px 22px;border-radius:10px;font-weight:bold}.result{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;padding:18px;min-height:120px}.error{color:#b91c1c;font-weight:bold}.success{color:#166534;font-weight:bold}.topbar{display:flex;justify-content:space-between;gap:12px;align-items:center}.links a{margin-left:10px}.limit{background:#eef2ff;color:#3730a3;border-radius:999px;padding:6px 10px;font-weight:bold}.brain-note{background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:12px;margin:12px 0}@media(max-width:850px){.grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:560px){.grid{grid-template-columns:1fr}body{padding:16px}.secondary{display:block;margin:10px 0 0 0}.topbar{display:block}.links a{display:inline-block;margin:8px 8px 0 0}}
</style>
</head>
<body>
<div class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<div class="card">
<div class="topbar"><div><h1>Bringora</h1><p class="small">Private beta mode. From messy thoughts to clear action.</p></div><div class="links small"><span class="limit"><?php echo htmlspecialchars($tier); ?></span><span id="usage" class="limit"><?php echo $usedToday; ?>/<?php echo $dailyLimit; ?> today</span><a href="brain.php">My Brain</a><a href="saved_outputs.php">Saved Outputs</a><a href="status.php">Status</a><a href="api-debug.php">API Debug</a><a href="privacy.php">Privacy</a><a href="terms.php">Terms</a><a href="support-center.php">Support</a><a href="?logout=1">Logout</a></div></div>
<p>Paste your messy thought. Bringora turns it into one clear structured output and a next best action.</p>
<div class="brain-note small"><strong>Context:</strong> If you filled out <a href="brain.php">My Bringora Brain</a> in this browser, Bringora will include it as optional local context when generating.</div>
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
<p class="small">Privacy MVP: prompt history is not saved. Browser-local My Bringora Brain is sent only during generation.</p>
<div id="status" class="small"></div>
<h3>Your Bringora output</h3>
<div id="result" class="result"></div>
</div></div>
<script>
const bringoraAccessToken = '<?php echo htmlspecialchars($accessToken, ENT_QUOTES); ?>';
function selectMode(mode,btn){document.getElementById('mode').value=mode;document.querySelectorAll('.choice').forEach(c=>c.classList.remove('selected'));btn.classList.add('selected')}
function readLocalBrain(){try{const data=JSON.parse(localStorage.getItem('bringora_brain_v1')||'{}');return data&&typeof data==='object'?data:{}}catch(e){return {}}}
async function readJsonOrError(response){const text=await response.text();try{return JSON.parse(text)}catch(e){return {success:false,error:'Server returned a non-JSON response. Open API Debug for details. HTTP '+response.status}}}
function showApiError(target,message){target.innerHTML='<span class="error">'+message+' <a href="api-debug.php">Open API Debug</a></span>'}
async function runBringora(){const prompt=document.getElementById('promptText').value.trim();const mode=document.getElementById('mode').value;const status=document.getElementById('status');const result=document.getElementById('result');const local_context=readLocalBrain();result.textContent='';status.textContent='';if(!prompt){status.innerHTML='<span class="error">Please paste your rough thought first.</span>';return}status.textContent='Thinking...';try{const response=await fetch('api.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-Bringora-Access':bringoraAccessToken},body:JSON.stringify({prompt,mode,local_context})});const data=await readJsonOrError(response);if(!response.ok||!data.success){showApiError(status,data.error||'Something went wrong.');return}result.textContent=data.result;if(data.usage){document.getElementById('usage').textContent=data.usage.used_today+'/'+data.usage.daily_limit+' today'}status.innerHTML='<span class="success">Done.</span>'}catch(e){showApiError(status,'Connection error. Please try again.')}}
function copyResult(){const r=document.getElementById('result').textContent;if(!r.trim()){alert('No result to copy yet.');return}navigator.clipboard.writeText(r).then(()=>alert('Copied.'))}
async function saveResult(){const output=document.getElementById('result').textContent.trim();const mode=document.getElementById('mode').value;const status=document.getElementById('status');if(!output){alert('No result to save yet.');return}status.textContent='Saving...';try{const response=await fetch('save_output.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-Bringora-Access':bringoraAccessToken},body:JSON.stringify({output,mode})});const data=await readJsonOrError(response);if(!response.ok||!data.success){showApiError(status,data.error||'Could not save output.');return}status.innerHTML='<span class="success">Saved. <a href="saved_outputs.php">View saved outputs</a>.</span>'}catch(e){showApiError(status,'Connection error. Please try again.')}}
</script>
</body>
</html>
