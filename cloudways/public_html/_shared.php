<?php
if (!function_exists('bringora_nav')) {
    function bringora_nav(string $active = ''): void
    {
        $links = [
            'app' => ['App', 'index.php'],
            'landing' => ['Landing', 'landing.php'],
            'pricing' => ['Pricing', 'pricing.php'],
            'faq' => ['FAQ', 'faq.php'],
            'use-cases' => ['Use Cases', 'use-cases.php'],
            'compare' => ['Compare', 'compare.php'],
            'brain' => ['My Brain', 'brain.php'],
            'status' => ['Status', 'status.php'],
            'support' => ['Support', 'support-center.php'],
        ];
        echo '<style>
        .bringora-nav{max-width:1100px;margin:0 auto 18px auto;background:#111827;color:#fff;border-radius:18px;padding:14px 16px;box-shadow:0 8px 30px rgba(0,0,0,.12);display:flex;align-items:center;justify-content:space-between;gap:12px;box-sizing:border-box}
        .bringora-brand{font-weight:800;letter-spacing:.2px;white-space:nowrap}.bringora-nav-links{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}.bringora-nav a{color:#dbeafe;text-decoration:none;font-size:14px;padding:8px 10px;border-radius:999px}.bringora-nav a:hover,.bringora-nav a.active{background:#2563eb;color:#fff}@media(max-width:760px){.bringora-nav{display:block;margin-bottom:14px}.bringora-brand{margin-bottom:10px}.bringora-nav-links{justify-content:flex-start}.bringora-nav a{font-size:13px;padding:7px 9px}}
        </style>';
        echo '<nav class="bringora-nav"><div class="bringora-brand">Bringora</div><div class="bringora-nav-links">';
        foreach ($links as $key => $link) {
            $class = $key === $active ? ' class="active"' : '';
            echo '<a' . $class . ' href="' . htmlspecialchars($link[1], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($link[0], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        echo '</div></nav>';
    }
}
