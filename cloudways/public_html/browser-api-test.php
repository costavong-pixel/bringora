<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}
$config = require $configPath;
require_once __DIR__ . '/../private_html/auth.php';

$authPayload = bringora_read_auth_payload($config);
if ($authPayload === null) {
    header('Location: index.php');
    exit;
}
$accessToken = bringora_beta_access_token($config);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bringora Browser API Test</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:24px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);border:1px solid #dbe3ef;margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.btn{background:#2563eb;color:#fff;border:0;border-radius:12px;padding:13px 16px;font-weight:bold}.row{display:grid;grid-template-columns:180px 1fr;gap:12px;border-bottom:1px solid #e5e7eb;padding:12px 0}.row:last-child{border-bottom:0}.label{font-weight:bold}.ok{color:#166534;font-weight:bold}.fail{color:#b91c1c;font-weight:bold}.not{color:#92400e;font-weight:bold}.box{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:14px;overflow:auto}@media(max-width:760px){body{padding:16px}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="wrap">
<?php require __DIR__ . '/_nav.php'; ?>
<section class="card">
<h1>Browser API Test</h1>
<p class="small">Protected diagnostic page. This runs a real browser fetch to api.php using the same header and JSON path as the main app. It does not print secrets.</p>
<button class="btn" type="button" onclick="runBrowserApiTest()">Run Browser API Test</button>
</section>
<section class="card">
<div class="row"><div class="label">State</div><div id="state" class="not">not run</div></div>
<div class="row"><div class="label">HTTP code</div><div id="httpCode"></div></div>
<div class="row"><div class="label">Duration</div><div id="duration"></div></div>
<div class="row"><div class="label">Message</div><div id="message">Click Run Browser API Test.</div></div>
</section>
<section class="card">
<h2>Response preview</h2>
<div id="preview" class="box"></div>
</section>
<section class="card">
<h2>How to read this</h2>
<p class="small">If App API Test passes but Browser API Test fails, the blocker is likely browser-side fetch behavior, cookies, JavaScript, mixed content, or blocked request handling. If Browser API Test passes but the main app fails, the blocker is likely page-specific JavaScript or UI state.</p>
</section>
</main>
<script>
const token = '<?php echo htmlspecialchars($accessToken, ENT_QUOTES); ?>';
function setState(value){const el=document.getElementById('state');el.textContent=value;el.className=value==='ok'?'ok':(value==='fail'?'fail':'not')}
async function readText(response){try{return await response.text()}catch(e){return ''}}
async function runBrowserApiTest(){
  setState('not run');
  document.getElementById('httpCode').textContent='';
  document.getElementById('duration').textContent='';
  document.getElementById('message').textContent='Running...';
  document.getElementById('preview').textContent='';
  const payload={prompt:'Reply with only OK. This is a browser API diagnostic test.',mode:'write',local_context:{style:'short and direct',constraints:'browser diagnostic only'}};
  const started=performance.now();
  try{
    const response=await fetch('api.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-Bringora-Access':token},body:JSON.stringify(payload)});
    const ms=Math.round(performance.now()-started);
    const text=await readText(response);
    document.getElementById('httpCode').textContent=String(response.status);
    document.getElementById('duration').textContent=ms+' ms';
    document.getElementById('preview').textContent=text.slice(0,1200);
    let data=null;
    try{data=JSON.parse(text)}catch(e){}
    if(response.ok && data && data.success){setState('ok');document.getElementById('message').textContent='Browser fetch returned success.';return}
    setState('fail');
    document.getElementById('message').textContent=(data&&data.error)?data.error:'Browser fetch did not return successful JSON.';
  }catch(e){
    const ms=Math.round(performance.now()-started);
    setState('fail');
    document.getElementById('duration').textContent=ms+' ms';
    document.getElementById('message').textContent='Browser fetch failed: '+e.message;
  }
}
</script>
</body>
</html>
