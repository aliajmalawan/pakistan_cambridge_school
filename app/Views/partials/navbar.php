<?php
use App\Models\Menu;
use App\Models\MenuItem;

$mainMenu = Menu::bySlug('main');
$navItems = $mainMenu ? MenuItem::navTree((int) $mainMenu['id']) : [];

$basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';

// Both the current request and every nav href carry the /PCS subdirectory
// prefix (from BASE_URL) — strip it the same way on both sides, or every
// active-state comparison below silently fails to match.
$normalizePath = static function (string $url) use ($basePath): string {
    $path = parse_url($url, PHP_URL_PATH) ?: '/';
    if ($basePath !== '' && str_starts_with($path, $basePath)) $path = substr($path, strlen($basePath));
    $path = '/' . trim($path, '/');
    return $path === '//' ? '/' : $path;
};

$currentPath = $normalizePath($_SERVER['REQUEST_URI'] ?? '/');

$labelOverrides = [
    'About Us' => 'About',
    'Academic Overview' => 'Academics',
    'Our Faculty' => 'Faculty',
];

$renderLabel = static function (string $label) use ($labelOverrides): string {
    return $labelOverrides[$label] ?? $label;
};
?>
<header class="site-header" id="siteHeader">
    <div class="topbar">
        <div class="container topbar-inner">
            <a class="brand topbar-brand" href="<?= url('/') ?>" aria-label="Pakistan Cambridge School home">
                <img class="brand-logo" src="<?= e(logo_url(96)) ?>" alt="Pakistan Cambridge School logo">
                <span class="brand-copy"><strong>Pakistan Cambridge School</strong><small>Hafizabad</small></span>
            </a>
            <div class="topbar-contact">
                <?php if ($phone = setting('phone')): ?><a class="top-contact-item" href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>"><span class="contact-circle"><?= icon('phone', 13) ?></span><span><small>Call Us</small><b><?= e($phone) ?></b></span></a><?php endif; ?>
                <?php if ($email = setting('email')): ?><a class="top-contact-item" href="mailto:<?= e($email) ?>"><span class="contact-circle"><?= icon('mail', 13) ?></span><span><small>Email Us</small><b><?= e($email) ?></b></span></a><?php endif; ?>
                <?php if ($facebook = setting('facebook')): ?><a class="top-social facebook" href="<?= e($facebook) ?>" target="_blank" rel="noopener">f</a><?php endif; ?>
                <?php if ($youtube = setting('youtube')): ?><a class="top-social youtube" href="<?= e($youtube) ?>" target="_blank" rel="noopener">▶</a><?php endif; ?>
                <?php if ($whatsapp = setting('whatsapp')): $waDigits = preg_replace('/\D/', '', $whatsapp); if (str_starts_with($waDigits, '0')) { $waDigits = '92' . substr($waDigits, 1); } elseif (!str_starts_with($waDigits, '92')) { $waDigits = '92' . $waDigits; } ?><a class="top-social whatsapp" href="https://wa.me/<?= e($waDigits) ?>" target="_blank" rel="noopener"><svg width="15" height="15" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.48 1.32 5L2 22l5.25-1.38c1.44.79 3.06 1.2 4.79 1.2h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.91-7.02A9.85 9.85 0 0012.04 2zm5.8 14.16c-.24.68-1.4 1.32-1.93 1.4-.5.08-1.12.12-1.8-.11-.42-.14-.96-.32-1.65-.63-2.9-1.25-4.8-4.17-4.94-4.36-.14-.19-1.18-1.57-1.18-3 0-1.42.75-2.12 1.02-2.41.27-.29.58-.36.78-.36.19 0 .39 0 .56.01.18.01.42-.07.66.5.24.58.83 2.01.9 2.16.07.15.12.32.02.51-.1.19-.15.31-.3.48-.15.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.63 2.02 1.12 1 2.06 1.31 2.36 1.46.29.15.46.13.63-.08.17-.2.72-.84.91-1.13.19-.29.38-.24.63-.15.26.1 1.65.78 1.93.92.29.15.48.22.55.34.07.13.07.75-.17 1.43z"/></svg></a><?php endif; ?>
                <a class="top-apply" href="<?= url('/admissions') ?>">Apply Now <span>↗</span></a>
            </div>
            <button class="nav-toggle" type="button" aria-label="Open navigation" aria-expanded="false" data-nav-toggle>
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <nav class="navbar" aria-label="Primary navigation">
        <div class="container nav-inner">
            <div class="nav-menu" data-nav-menu>
                <?php foreach ($navItems as $item):
                    $children = $item['children'] ?? [];
                    $href = $item['href'] ?? '#';
                    $isDropdown = count($children) > 0 || ($item['link_type'] ?? '') === 'none';
                    $isActive = ($href !== '#' && $normalizePath($href) === $currentPath);
                    $childActiveStates = array_map(
                        static fn($child) => (($child['href'] ?? '#') !== '#' && $normalizePath($child['href']) === $currentPath),
                        $children
                    );
                    $groupActive = $isActive || in_array(true, $childActiveStates, true);
                ?>
                    <?php if ($isDropdown): ?>
                        <div class="nav-dropdown <?= $groupActive ? 'active' : '' ?>" data-dropdown>
                            <button type="button" class="nav-drop-toggle" data-dropdown-toggle aria-expanded="false">
                                <?= e($renderLabel($item['label'])) ?> <span class="chevron">⌄</span>
                            </button>
                            <div class="nav-dropdown-menu">
                                <?php foreach ($children as $i => $child): $childHref = $child['href'] ?? '#'; ?>
                                    <a href="<?= e($childHref) ?>" class="<?= $childActiveStates[$i] ? 'active' : '' ?>"><span><?= e($renderLabel($child['label'])) ?></span><b>→</b></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= e($href) ?>" class="<?= $isActive ? 'active' : '' ?>"><?= e($renderLabel($item['label'])) ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>
</header>
