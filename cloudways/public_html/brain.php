<?php
$configPath = __DIR__ . '/../private_html/private_config.php';
if (!file_exists($configPath)) {
    die('Private config file not found.');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Bringora Brain</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:28px}.wrap{max-width:980px;margin:auto}.card{background:#fff;padding:26px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08);margin-bottom:18px}.small{font-size:14px;color:#64748b;line-height:1.5}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.field{background:#f8fafc;border:1px solid #cbd5e1;border-radius:14px;padding:14px}.field label{display:block;font-weight:bold;margin-bottom:8px}textarea{width:100%;min-height:96px;border:1px solid #cbd5e1;border-radius:10px;padding:12px;box-sizing:border-box;font-size:15px}.primary{background:#2563eb;color:#fff;border:0;border-radius:10px;padding:13px 18px;font-weight:bold}.secondary{background:#111827;color:#fff;border:0;border-radius:10px;padding:13px 18px;font-weight:bold;margin-left:8px}.danger{background:#b91c1c;color:#fff;border:0;border-radius:10px;padding:13px 18px;font-weight:bold;margin-left:8px}.success{color:#166534;font-weight:bold}.preview{white-space:pre-wrap;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;padding:16px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}@media(max-width:760px){body{padding:16px}.grid{grid-template-columns:1fr}.secondary,.danger{display:block;margin:10px 0 0 0;width:100%}.primary{width:100%}.top{display:block}}
</style>
</head>
<body>
<main class="wrap">
<section class="card top">
<div>
<h1>My Bringora Brain</h1>
<p class="small">Optional local profile. This is saved in your browser only for now. It helps Bringora understand your style, habits, constraints, and active projects.</p>
</div>
<a href="index.php">Back to Bringora</a>
</section>
<section class="card">
<div class="grid">
<div class="field"><label for="style">My Style</label><textarea id="style" placeholder="Example: direct, practical, short answers, ask one question at a time."></textarea></div>
<div class="field"><label for="habits">My Habits</label><textarea id="habits" placeholder="Example: I compare cost carefully, I get annoyed by vague steps, I work better with examples."></textarea></div>
<div class="field"><label for="routine">My Routine</label><textarea id="routine" placeholder="Example: limited mobility, often using phone, mornings are slower, evening PSW visit."></textarea></div>
<div class="field"><label for="priorities">My Priorities</label><textarea id="priorities" placeholder="Example: save time, reduce cost, build AppSumo-ready MVP, avoid overbuilding."></textarea></div>
<div class="field"><label for="constraints">My Constraints</label><textarea id="constraints" placeholder="Example: weak laptop, need mobile-friendly steps, avoid expensive providers."></textarea></div>
<div class="field"><label for="projects">My Projects</label><textarea id="projects" placeholder="Example: Bringora, Barnd AI, Slab video platform, AI voice assistant."></textarea></div>
</div>
<p><button class="primary" onclick="saveBrain()">Save My Brain</button><button class="secondary" onclick="copyBrain()">Copy Summary</button><button class="danger" onclick="clearBrain()">Clear</button></p>
<p id="status" class="small"></p>
</section>
<section class="card">
<h2>Preview</h2>
<div id="preview" class="preview"></div>
</section>
</main>
<script>
const key='bringora_brain_v1';
const fields=['style','habits','routine','priorities','constraints','projects'];
const labels={style:'My Style',habits:'My Habits',routine:'My Routine',priorities:'My Priorities',constraints:'My Constraints',projects:'My Projects'};
function readBrain(){try{return JSON.parse(localStorage.getItem(key)||'{}')}catch(e){return {}}}
function writeBrain(data){localStorage.setItem(key,JSON.stringify(data))}
function loadBrain(){const data=readBrain();fields.forEach(f=>{document.getElementById(f).value=data[f]||''});renderPreview()}
function currentBrain(){const data={};fields.forEach(f=>{data[f]=document.getElementById(f).value.trim()});return data}
function brainText(){const data=currentBrain();return fields.map(f=>labels[f]+':\n'+(data[f]||'(blank)')).join('\n\n')}
function renderPreview(){document.getElementById('preview').textContent=brainText()}
function saveBrain(){const data=currentBrain();writeBrain(data);renderPreview();document.getElementById('status').innerHTML='<span class="success">Saved in this browser.</span>'}
function copyBrain(){navigator.clipboard.writeText(brainText()).then(()=>{document.getElementById('status').innerHTML='<span class="success">Copied.</span>'})}
function clearBrain(){if(!confirm('Clear My Bringora Brain from this browser?'))return;localStorage.removeItem(key);loadBrain();document.getElementById('status').textContent='Cleared.'}
fields.forEach(f=>document.addEventListener('input',e=>{if(e.target&&e.target.id===f)renderPreview()}));
loadBrain();
</script>
</body>
</html>
