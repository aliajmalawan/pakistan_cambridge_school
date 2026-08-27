<?php $user = App\Core\Auth::user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Admin') ?> — Pakistan Cambridge School Admin</title>
<meta name="robots" content="noindex,nofollow">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(logo_url(32)) ?>">
<!-- Same self-hosted fonts and same compiled stylesheet as the public site: no
     Google Fonts request, no Tailwind CDN compiler, and one typeface everywhere. -->
<link rel="preload" as="font" type="font/woff2" href="<?= asset('fonts/in400.woff2') ?>" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="<?= asset('fonts/pjs700.woff2') ?>" crossorigin>
<link rel="stylesheet" href="<?= asset_v('css/app.css') ?>">
</head>
<body class="bg-paper text-ink-900 font-body antialiased">
<div class="min-h-screen lg:grid lg:grid-cols-[264px_minmax(0,1fr)]">

  <!-- Sidebar -->
  <aside class="bg-navy text-[#C7D3DE] lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
      <img src="<?= e(logo_url(96)) ?>" alt="" width="40" height="40" class="h-10 w-10 object-contain">
      <div class="leading-tight">
        <div class="font-heading font-bold text-white text-sm">Pakistan Cambridge School Admin</div>
        <div class="text-[11px] text-[#8FA8C2]">CMS &amp; Website</div>
      </div>
      <button id="side-toggle" class="lg:hidden ml-auto w-10 h-10 inline-flex items-center justify-center rounded-lg hover:bg-white/10" aria-label="Toggle menu" aria-expanded="false" aria-controls="side-nav">☰</button>
    </div>
    <nav id="side-nav" class="hidden lg:block px-3 py-4 text-sm" aria-label="Admin">
      <?php
      $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
      $item = function (string $path, string $label, string $icon) use ($currentPath): string {
          $full = parse_url(url($path), PHP_URL_PATH);
          $active = $path === '/admin'
              ? rtrim($currentPath, '/') === rtrim($full, '/')
              : str_starts_with($currentPath, $full);
          $cls = $active
              ? 'bg-white/10 text-white font-semibold relative before:absolute before:-left-3 before:top-1.5 before:bottom-1.5 before:w-[3px] before:rounded before:bg-gold'
              : 'hover:bg-white/5';
          return '<a href="' . url($path) . '" class="flex items-center gap-3 px-3 h-11 rounded-lg transition ' . $cls . '">'
              . '<span class="w-5 text-center" aria-hidden="true">' . $icon . '</span>' . e($label) . '</a>';
      };
      $group = fn(string $t): string => '<div class="px-3 pt-5 pb-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-[#8FA8C2]">' . $t . '</div>';
      echo $item('/admin', 'Dashboard', '▦');
      if (in_array($user['role'] ?? 'editor', ['admin', 'superadmin'], true)) {
          echo $item('/admin/analytics', 'Analytics', '📈');
      }
      echo $group('Website Content');
      echo $item('/admin/menu', 'Menu Builder', '☰');
      echo $item('/admin/sliders', 'Homepage Sliders', '🖼');
      echo $item('/admin/pages', 'Pages', '📄');
      echo $item('/admin/about', 'About Page', '🏫');
      echo $item('/admin/values', 'Core Values', '✦');
      echo $item('/admin/news', 'News & Notices', '📰');
      echo $item('/admin/blogs', 'Blogs', '✍');
      echo $item('/admin/programs', 'Programs', '🎓');
      echo $item('/admin/academics', 'Academics', '📚');
      echo $item('/admin/facilities', 'Facilities', '🏢');
      echo $item('/admin/fees', 'Fee Structure', '💳');
      echo $item('/admin/calendar', 'Academic Calendar', '🗓');
      echo $item('/admin/leadership', 'Leadership', '🏛');
      echo $item('/admin/faculty', 'Faculty', '👥');
      echo $item('/admin/testimonials', 'Testimonials', '💬');
      echo $item('/admin/gallery', 'Gallery', '📷');
      echo $group('Inbox');
      echo $item('/admin/admissions', 'Admission Applications', '📝');
      echo $item('/admin/messages', 'Contact Messages', '✉');
      echo $group('System');
      echo $item('/admin/settings', 'Site Settings', '⚙');
      echo $item('/admin/users', 'Admin Users', '🔐');
      ?>
      <div class="px-3 pt-6 pb-4">
        <a href="<?= url('/') ?>" target="_blank" rel="noopener" class="text-xs text-[#8FA8C2] hover:text-white transition">View public website ↗</a>
      </div>
    </nav>
  </aside>

  <!-- Main -->
  <div class="min-w-0">
    <header class="sticky top-0 z-30 bg-paper/95 backdrop-blur border-b border-ink-100 px-6 h-16 flex items-center justify-between gap-4">
      <h1 class="font-heading font-bold text-lg text-navy truncate"><?= e($pageTitle ?? 'Admin') ?></h1>
      <div class="flex items-center gap-3 shrink-0">
        <span class="hidden sm:block text-sm text-ink-500"><?= e($user['name'] ?? '') ?> · <span class="uppercase text-[10px] font-bold tracking-wide text-gold-deep"><?= e($user['role'] ?? '') ?></span></span>
        <form method="post" action="<?= url('/admin/logout') ?>">
          <?= App\Core\Csrf::field() ?>
          <button class="inline-flex items-center h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">Sign out</button>
        </form>
      </div>
    </header>

    <main class="p-6">
      <?php if ($msg = flash('success')): ?>
      <div class="mb-5 flex gap-3 items-start rounded-lg border-l-4 border-success bg-success/10 px-4 py-3 text-sm" role="status">
        <span class="font-bold text-success" aria-hidden="true">✓</span><span><?= e($msg) ?></span>
      </div>
      <?php endif; ?>
      <?php if ($msg = flash('error')): ?>
      <div class="mb-5 flex gap-3 items-start rounded-lg border-l-4 border-danger bg-danger/10 px-4 py-3 text-sm" role="alert">
        <span class="font-bold text-danger" aria-hidden="true">!</span><span><?= e($msg) ?></span>
      </div>
      <?php endif; ?>

      <?= $content ?>
    </main>
  </div>
</div>
<script>
(function(){
  var t = document.getElementById('side-toggle'), n = document.getElementById('side-nav');
  if (t && n) t.addEventListener('click', function(){
    var open = n.classList.toggle('hidden') === false;
    t.setAttribute('aria-expanded', String(open));
  });
})();
</script>
</body>
</html>
