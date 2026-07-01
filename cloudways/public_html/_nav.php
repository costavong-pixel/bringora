<?php
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
$navGroups = [
    'Core' => [
        'index.php' => 'App',
        'brain.php' => 'My Brain',
        'saved_outputs.php' => 'Saved Outputs',
    ],
    'Product' => [
        'landing.php' => 'Landing',
        'pricing.php' => 'Pricing',
        'faq.php' => 'FAQ',
        'roadmap.php' => 'Roadmap',
        'compare.php' => 'Compare',
    ],
    'Beta' => [
        'examples.php' => 'Examples',
        'starter-inputs.php' => 'Starter Inputs',
        'use-cases.php' => 'Use Cases',
        'demo-script.php' => 'Demo Script',
        'onboarding.php' => 'Onboarding',
        'checklist.php' => 'Checklist',
        'support-center.php' => 'Support',
    ],
    'Ops' => [
        'status.php' => 'Status',
        'api-debug.php' => 'API Debug',
        'provider-test.php' => 'Provider Test',
        'health.php' => 'Health',
    ],
];
?>
<style>
.br-nav{background:#fff;border:1px solid #dbe3ef;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.06);padding:16px;margin:0 0 18px}.br-nav-top{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:12px}.br-brand{font-weight:900;color:#111827;text-decoration:none}.br-status{font-size:13px;color:#64748b}.br-nav-groups{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.br-group-title{font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:8px}.br-links{display:flex;flex-wrap:wrap;gap:8px}.br-links a{display:inline-block;text-decoration:none;color:#1f2937;background:#f8fafc;border:1px solid #e2e8f0;border-radius:999px;padding:8px 11px;font-size:14px}.br-links a.active{background:#2563eb;color:#fff;border-color:#2563eb}@media(max-width:1000px){.br-nav-groups{grid-template-columns:repeat(2,1fr)}}@media(max-width:650px){.br-nav-groups{grid-template-columns:1fr}.br-nav-top{display:block}.br-status{margin-top:6px}.br-links a{font-size:13px;padding:8px 10px}}
</style>
<nav class="br-nav" aria-label="Bringora navigation">
    <div class="br-nav-top">
        <a class="br-brand" href="landing.php">Bringora</a>
        <div class="br-status">From messy thoughts to clear action</div>
    </div>
    <div class="br-nav-groups">
        <?php foreach ($navGroups as $groupName => $links): ?>
            <div>
                <div class="br-group-title"><?php echo htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="br-links">
                    <?php foreach ($links as $href => $label): ?>
                        <a class="<?php echo $currentPage === $href ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</nav>
