<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<meta name="robots" content="noindex,nofollow">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(logo_url(32)) ?>">
<link rel="preload" as="font" type="font/woff2" href="<?= asset('fonts/in400.woff2') ?>" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="<?= asset('fonts/pjs700.woff2') ?>" crossorigin>
<link rel="stylesheet" href="<?= asset_v('css/app.css') ?>">
</head>
<body class="min-h-screen bg-navy font-body text-ink-700 flex items-center justify-center p-4"
      style="background-image:radial-gradient(ellipse 800px 400px at 85% -5%, rgba(199,150,44,0.15), transparent 60%);">
<div class="w-full max-w-md">
  <div class="text-center mb-8">
    <img src="<?= e(logo_url(192)) ?>" alt="" width="64" height="64" class="h-16 w-16 object-contain mx-auto mb-4">
    <h1 class="font-heading font-bold text-2xl text-white">Pakistan Cambridge School Admin</h1>
    <p class="text-sm text-[#8FA8C2] mt-1"><?= e(setting('site_name')) ?></p>
  </div>

  <div class="bg-white rounded-2xl shadow-2xl p-8">
    <?php if ($msg = App\Core\Session::flash('error')): ?>
    <div class="mb-5 rounded-lg border-l-4 border-danger bg-danger/10 px-4 py-3 text-sm" role="alert"><?= e($msg) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= url('/admin/login') ?>">
      <?= App\Core\Csrf::field() ?>
      <div class="flex flex-col gap-1.5 mb-4">
        <label for="email" class="text-sm font-medium">Email</label>
        <input id="email" name="email" type="email" required autofocus autocomplete="username" value="<?= old('email') ?>"
               class="h-11 rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
      </div>
      <div class="flex flex-col gap-1.5 mb-6">
        <label for="password" class="text-sm font-medium">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password"
               class="h-11 rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
      </div>
      <button type="submit" class="w-full h-12 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-gold focus-visible:outline-offset-2">Sign In</button>
    </form>
  </div>
  <p class="text-center text-xs text-[#8FA8C2] mt-6">Access restricted to authorized staff of Pakistan Cambridge School.</p>
</div>
<?php clear_old(); ?>
</body>
</html>
