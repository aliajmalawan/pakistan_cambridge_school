<?php
$seo=$seo ?? App\Core\Seo::make()->title($pageTitle ?? setting('site_name',APP_NAME))->description($metaDescription ?? setting('tagline','Pakistan Cambridge School Hafizabad'))->canonical($_SERVER['REQUEST_URI'] ?? '/');
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#0A4B8C">
<?= $seo->render() ?><link rel="icon" type="image/png" sizes="32x32" href="<?= e(logo_url(32)) ?>"><link rel="apple-touch-icon" href="<?= e(logo_url(192)) ?>">
<link rel="preload" as="font" type="font/woff2" href="<?= asset('fonts/in400.woff2') ?>" crossorigin><link rel="preload" as="font" type="font/woff2" href="<?= asset('fonts/in700.woff2') ?>" crossorigin>
<link rel="stylesheet" href="<?= asset_v('css/site.css') ?>"><script>document.documentElement.classList.add('js')</script></head><body>
<a href="#main" class="skip-link">Skip to content</a><?php App\Core\View::partial('partials/navbar'); ?>
<?php if($msg=flash('success')):?><div class="site-alert success" role="status"><?=e($msg)?></div><?php endif;?><?php if($msg=flash('error')):?><div class="site-alert error" role="alert"><?=e($msg)?></div><?php endif;?>
<main id="main"><?= $content ?></main><?php App\Core\View::partial('partials/footer'); ?><script src="<?= asset_v('js/site.js') ?>" defer></script>
</body></html>
