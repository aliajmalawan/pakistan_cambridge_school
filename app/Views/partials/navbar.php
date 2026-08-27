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
                <img class="brand-logo" src="<?= asset('img/pcs-logo.png') ?>" alt="Pakistan Cambridge School logo">
                <span class="brand-copy"><strong>Pakistan Cambridge School</strong><small>Hafizabad</small></span>
            </a>
            <div class="topbar-contact">
                <?php if ($phone = setting('phone')): ?><a class="top-contact-item" href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>"><span class="contact-circle"><?= icon('phone', 13) ?></span><span><small>Call Us</small><b><?= e($phone) ?></b></span></a><?php endif; ?>
                <?php if ($email = setting('email')): ?><a class="top-contact-item" href="mailto:<?= e($email) ?>"><span class="contact-circle"><?= icon('mail', 13) ?></span><span><small>Email Us</small><b><?= e($email) ?></b></span></a><?php endif; ?>
                <?php if ($facebook = setting('facebook')): ?><a class="top-social facebook" href="<?= e($facebook) ?>" target="_blank" rel="noopener">f</a><?php endif; ?>
                <?php if ($youtube = setting('youtube')): ?><a class="top-social youtube" href="<?= e($youtube) ?>" target="_blank" rel="noopener">▶</a><?php endif; ?>
                <?php if ($whatsapp = setting('whatsapp')): ?><a class="top-social whatsapp" href="<?= e($whatsapp) ?>" target="_blank" rel="noopener">◉</a><?php endif; ?>
                <a class="top-apply" href="<?= url('/admissions') ?>">Apply Now <span>↗</span></a>
            </div>
        </div>
    </div>

    <nav class="navbar" aria-label="Primary navigation">
        <div class="container nav-inner">
            <button class="nav-toggle" type="button" aria-label="Open navigation" aria-expanded="false" data-nav-toggle>
                <span></span><span></span><span></span>
            </button>
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
