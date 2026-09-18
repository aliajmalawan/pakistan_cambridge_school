<?php
use App\Models\Page;
$footerPages = [];
foreach (Page::systemPages() as $p) {
    if (($p['status'] ?? '') === 'published' && ($p['route'] ?? '') !== '/') $footerPages[] = ['title' => $p['title'], 'href' => url($p['route'])];
}
foreach (Page::contentPages() as $p) {
    if (($p['status'] ?? '') === 'published') $footerPages[] = ['title' => $p['title'], 'href' => url('/page/' . $p['slug'])];
}
?>
<footer class="site-footer" id="contact">
    <div class="container footer-grid">
        <div class="footer-brand">
            <img class="footer-logo" src="<?= e(logo_url(96)) ?>" alt="Pakistan Cambridge School">
            <h3>Pakistan Cambridge School</h3>
            <p><?= e(setting('footer_about', 'Building confident learners, responsible citizens and future leaders through purposeful education.')) ?></p>
            <div class="socials">
                <?php if ($facebook = setting('facebook')): ?><a href="<?= e($facebook) ?>" target="_blank" rel="noopener">f</a><?php endif; ?>
                <?php if (($youtube = setting('youtube')) && $youtube !== '#'): ?><a href="<?= e($youtube) ?>" target="_blank" rel="noopener">▶</a><?php endif; ?>
                <?php if ($whatsapp = setting('whatsapp')): ?><a href="<?= e($whatsapp) ?>" target="_blank" rel="noopener">◉</a><?php endif; ?>
            </div>
        </div>
        <div>
            <h4>Explore</h4>
            <?php foreach (array_slice($footerPages, 0, 5) as $item): ?><a href="<?= e($item['href']) ?>"><?= e($item['title']) ?></a><?php endforeach; ?>
        </div>
        <div>
            <h4>Admissions</h4>
            <a href="<?= url('/admissions') ?>">Admissions</a>
            <a href="<?= url('/programs') ?>">Academic Programs</a>
            <a href="<?= url('/fees') ?>">Fee Structure</a>
            <a href="<?= url('/downloads') ?>">Downloads & Forms</a>
        </div>
        <div>
            <h4>Contact</h4>
            <p><?= e(setting('address', 'Hafizabad, Punjab, Pakistan')) ?></p>
            <?php if ($phone = setting('phone')): ?><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>"><?= e($phone) ?></a><?php endif; ?>
            <?php if ($email = setting('email')): ?><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><?php endif; ?>
        </div>
    </div>
    <div class="footer-bottom"><div class="container"><span>© <?= date('Y') ?> Pakistan Cambridge School Hafizabad. All rights reserved.</span><span>Powered by EduPortal</span></div></div>
</footer>
