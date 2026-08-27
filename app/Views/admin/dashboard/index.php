<?php
use App\Core\Chart;

$show = fn(string $key): bool => in_array($key, $widgets, true);
$badges = ['pending' => 'bg-warning/15 text-warning', 'under_review' => 'bg-info/15 text-info', 'accepted' => 'bg-success/15 text-success', 'rejected' => 'bg-danger/15 text-danger'];
?>

<!-- Toolbar -->
<div class="flex items-center justify-between gap-3 mb-5 flex-wrap">
  <p class="text-sm text-ink-500"><?= format_date(date('Y-m-d'), 'l, j F Y') ?></p>
  <div class="flex gap-2">
    <?php if ($isAdmin): ?>
    <a href="<?= url('/admin/analytics') ?>" class="inline-flex items-center gap-2 h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19h16M6 15l4-5 3 3 5-7"/></svg>
      Full Analytics
    </a>
    <?php endif; ?>
    <button id="customize-toggle" class="inline-flex items-center gap-2 h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition" aria-expanded="false" aria-controls="customize-panel">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.2 2.2m8.4 8.4 2.2 2.2m0-12.8-2.2 2.2M7.8 16.2l-2.2 2.2"/></svg>
      Customize
    </button>
  </div>
</div>

<form id="customize-panel" method="post" action="<?= url('/admin/dashboard/prefs') ?>" class="hidden mb-6 bg-white rounded-xl border border-gold/40 shadow-kds-sm p-5">
  <?= App\Core\Csrf::field() ?>
  <h2 class="font-semibold mb-3">Visible sections</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
    <?php foreach ($allWidgets as $key => $label): ?>
    <label class="flex items-center gap-2.5 text-sm px-3 py-2.5 rounded-lg border border-ink-100 hover:bg-sunken/60 cursor-pointer">
      <input type="checkbox" name="widgets[]" value="<?= e($key) ?>" <?= $show($key) ? 'checked' : '' ?> class="w-[18px] h-[18px] accent-navy">
      <?= e($label) ?>
    </label>
    <?php endforeach; ?>
  </div>
  <div class="mt-4 flex gap-3">
    <button type="submit" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">Save Layout</button>
    <button type="button" id="customize-cancel" class="inline-flex items-center h-10 px-5 rounded-lg text-sm font-medium text-ink-700 hover:bg-sunken transition">Cancel</button>
  </div>
</form>

<?php if ($show('stats')): ?>
<!-- ============ KPI cards ============ -->
<div class="grid grid-cols-2 <?= $isAdmin ? 'xl:grid-cols-4' : 'xl:grid-cols-3' ?> gap-4">
  <a href="<?= url('/admin/admissions?status=pending') ?>" class="bg-white rounded-xl border <?= $pendingApps > 0 ? 'border-gold/50' : 'border-ink-100' ?> shadow-kds-sm p-5 hover:shadow-kds-md hover:-translate-y-0.5 transition">
    <div class="text-[11px] font-semibold uppercase tracking-wider text-ink-500">Pending Applications</div>
    <div class="text-3xl font-bold tabular mt-1 <?= $pendingApps > 0 ? 'text-gold-deep' : 'text-navy' ?>"><?= number_format($pendingApps) ?></div>
    <div class="text-xs text-ink-500 mt-1"><?= number_format($totalAdmissions) ?> received in total</div>
  </a>

  <a href="<?= url('/admin/messages') ?>" class="bg-white rounded-xl border <?= $unreadMessages > 0 ? 'border-gold/50' : 'border-ink-100' ?> shadow-kds-sm p-5 hover:shadow-kds-md hover:-translate-y-0.5 transition">
    <div class="text-[11px] font-semibold uppercase tracking-wider text-ink-500">Unread Messages</div>
    <div class="text-3xl font-bold tabular mt-1 <?= $unreadMessages > 0 ? 'text-gold-deep' : 'text-navy' ?>"><?= number_format($unreadMessages) ?></div>
    <div class="text-xs text-ink-500 mt-1"><?= number_format($totalMessages) ?> received in total</div>
  </a>

  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
    <div class="text-[11px] font-semibold uppercase tracking-wider text-ink-500">Applications This Month</div>
    <div class="flex items-baseline gap-2 mt-1 flex-wrap">
      <span class="text-3xl font-bold tabular text-navy"><?= number_format($appsThisMonth) ?></span>
      <?= Chart::delta($appsThisMonth, $appsLastMonth) ?>
    </div>
    <div class="text-xs text-ink-500 mt-1"><?= number_format($appsLastMonth) ?> last month</div>
  </div>

  <?php if ($isAdmin): ?>
  <a href="<?= url('/admin/analytics') ?>" class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5 hover:shadow-kds-md hover:-translate-y-0.5 transition">
    <div class="text-[11px] font-semibold uppercase tracking-wider text-ink-500">Page Views Today</div>
    <div class="flex items-baseline gap-2 mt-1 flex-wrap">
      <span class="text-3xl font-bold tabular text-navy"><?= number_format($viewsToday) ?></span>
      <?= Chart::delta($viewsToday, $viewsYesterday) ?>
    </div>
    <div class="text-xs text-ink-500 mt-1"><?= $onlineNow ?> online now · full analytics →</div>
  </a>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($show('attention')): ?>
<!-- ============ Needs attention ============ -->
<div class="mt-6 bg-white rounded-xl border border-ink-100 shadow-kds-sm">
  <div class="px-5 py-4 border-b border-ink-100"><h2 class="font-semibold">Needs Attention</h2></div>
  <?php if ($attention): ?>
  <div class="divide-y divide-ink-100">
    <?php foreach ($attention as $task): ?>
    <div class="flex items-center justify-between gap-4 px-5 py-4 flex-wrap">
      <div class="flex items-start gap-3 min-w-0">
        <span class="w-2 h-2 rounded-full mt-2 shrink-0 <?= $task['tone'] === 'warning' ? 'bg-warning' : 'bg-info' ?>" aria-hidden="true"></span>
        <div class="min-w-0">
          <div class="text-sm font-medium"><?= e($task['label']) ?></div>
          <div class="text-xs text-ink-500 mt-0.5"><?= e($task['detail']) ?></div>
        </div>
      </div>
      <a href="<?= url($task['href']) ?>" class="inline-flex items-center h-9 px-4 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0"><?= e($task['action']) ?></a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="px-5 py-10 text-center">
    <p class="font-heading font-semibold text-lg text-navy">Everything is up to date</p>
    <p class="text-sm text-ink-500 mt-1">No pending applications, unread messages or unpublished drafts.</p>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($show('trend') && $isAdmin): ?>
<!-- ============ Week at a glance ============ -->
<div class="mt-6 bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
  <div class="flex items-center justify-between gap-3 mb-4">
    <h2 class="font-semibold">This Week at a Glance</h2>
    <a href="<?= url('/admin/analytics') ?>" class="text-sm font-semibold text-navy hover:underline shrink-0">Full analytics →</a>
  </div>
  <?= Chart::line(
      [
          ['label' => 'Page Views',      'color' => Chart::BLUE,   'data' => array_values($weekViews)],
          ['label' => 'Unique Visitors', 'color' => Chart::ORANGE, 'data' => array_values($weekUnique)],
      ],
      array_map(fn($d) => date('D', strtotime($d)), array_keys($weekViews)),
      'Page views and unique visitors over the last 7 days',
      'Visits are counted as people browse the public website.'
  ) ?>
</div>
<?php endif; ?>

<?php if ($show('recent')): ?>
<!-- ============ Recent lists ============ -->
<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-ink-100">
      <h2 class="font-semibold">Recent Applications</h2>
      <a href="<?= url('/admin/admissions') ?>" class="text-sm font-semibold text-navy hover:underline">View all →</a>
    </div>
    <div class="divide-y divide-ink-100">
      <?php foreach ($recentAdmissions as $a): ?>
      <a href="<?= url('/admin/admissions/show/' . $a['id']) ?>" class="flex items-center justify-between gap-3 px-5 py-3.5 hover:bg-sunken/50 transition">
        <div class="min-w-0">
          <div class="text-sm font-medium truncate"><?= e($a['student_name']) ?> <span class="text-ink-500 font-normal">— <?= e($a['class_applied']) ?></span></div>
          <div class="text-xs text-ink-500 mt-0.5 tabular"><?= e($a['app_no']) ?> · <?= time_ago($a['created_at']) ?></div>
        </div>
        <span class="shrink-0 inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide <?= $badges[$a['status']] ?>"><?= e(App\Models\Admission::STATUSES[$a['status']]) ?></span>
      </a>
      <?php endforeach; ?>
      <?php if (!$recentAdmissions): ?>
      <div class="px-5 py-10 text-center text-sm text-ink-500">No applications yet — submissions from the online form appear here.</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-ink-100">
      <h2 class="font-semibold">Recent Messages</h2>
      <a href="<?= url('/admin/messages') ?>" class="text-sm font-semibold text-navy hover:underline">View all →</a>
    </div>
    <div class="divide-y divide-ink-100">
      <?php foreach ($recentMessages as $m): ?>
      <a href="<?= url('/admin/messages/show/' . $m['id']) ?>" class="block px-5 py-3.5 hover:bg-sunken/50 transition <?= $m['is_read'] ? '' : 'bg-navy-50/40' ?>">
        <div class="flex items-center gap-2">
          <?php if (!$m['is_read']): ?><span class="w-2 h-2 rounded-full bg-navy shrink-0" aria-hidden="true"></span><?php endif; ?>
          <span class="text-sm <?= $m['is_read'] ? 'font-medium' : 'font-bold' ?> truncate"><?= e($m['subject']) ?></span>
        </div>
        <div class="text-xs text-ink-500 mt-0.5"><?= e($m['name']) ?> · <?= time_ago($m['created_at']) ?></div>
      </a>
      <?php endforeach; ?>
      <?php if (!$recentMessages): ?>
      <div class="px-5 py-10 text-center text-sm text-ink-500">No messages yet.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if ($show('content')): ?>
<!-- ============ Content summary ============ -->
<div class="mt-6 bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
  <h2 class="font-semibold mb-4">Content Summary</h2>
  <dl class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <?php
    $counts = [
        ['Published posts', $publishedNews, '/admin/news'],
        ['Unpublished drafts', $draftNews, '/admin/news'],
        ['Faculty profiles', $facultyCount, '/admin/faculty'],
        ['Gallery photos', $galleryCount, '/admin/gallery'],
    ];
    foreach ($counts as [$label, $value, $href]): ?>
    <a href="<?= url($href) ?>" class="rounded-lg border border-ink-100 px-4 py-3 hover:bg-sunken/50 transition">
      <dt class="text-[11px] font-semibold uppercase tracking-wider text-ink-500"><?= e($label) ?></dt>
      <dd class="text-2xl font-bold tabular text-navy mt-0.5"><?= number_format((int) $value) ?></dd>
    </a>
    <?php endforeach; ?>
  </dl>
</div>
<?php endif; ?>

<?php if ($show('activity')): ?>
<!-- ============ Activity ============ -->
<div class="mt-6 bg-white rounded-xl border border-ink-100 shadow-kds-sm">
  <div class="px-5 py-4 border-b border-ink-100"><h2 class="font-semibold">Activity Feed</h2></div>
  <ol class="relative px-5 py-4 before:absolute before:left-[30px] before:top-6 before:bottom-6 before:w-0.5 before:bg-ink-100">
    <?php foreach ($activity as $log): ?>
    <?php [$icon, $tone] = App\Models\ActivityLog::SUBJECT_META[$log['subject_type']] ?? ['◆', 'info']; ?>
    <li class="relative flex gap-4 py-2.5 pl-1">
      <span class="relative z-10 w-8 h-8 rounded-lg shrink-0 inline-flex items-center justify-center text-sm
        <?= $tone === 'warning' ? 'bg-warning/15' : ($tone === 'success' ? 'bg-success/15' : 'bg-info/15') ?>" aria-hidden="true"><?= $icon ?></span>
      <div class="min-w-0 pt-0.5">
        <div class="text-sm leading-snug">
          <span class="font-medium"><?= e($log['actor']) ?></span>
          <span class="text-ink-500"><?= e(str_replace('_', ' ', $log['action'])) ?></span>
          <span class="font-medium text-navy"><?= e($log['subject_label']) ?></span>
        </div>
        <time class="text-xs text-ink-500"><?= time_ago($log['created_at']) ?></time>
      </div>
    </li>
    <?php endforeach; ?>
    <?php if (!$activity): ?>
    <li class="py-8 text-center text-sm text-ink-500">No activity recorded yet.</li>
    <?php endif; ?>
  </ol>
</div>
<?php endif; ?>

<?php if ($show('quick')): ?>
<!-- ============ Quick actions ============ -->
<div class="mt-6 bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
  <h2 class="font-semibold mb-3">Quick Actions</h2>
  <div class="flex flex-wrap gap-2.5">
    <a href="<?= url('/admin/news/create') ?>" class="inline-flex items-center h-9 px-4 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">+ Publish News / Notice</a>
    <a href="<?= url('/admin/menu') ?>" class="inline-flex items-center h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">Edit Menus</a>
    <a href="<?= url('/admin/gallery') ?>" class="inline-flex items-center h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">Upload Photos</a>
    <a href="<?= url('/admin/sliders') ?>" class="inline-flex items-center h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">Homepage Sliders</a>
    <a href="<?= url('/admin/settings') ?>" class="inline-flex items-center h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">Site Settings</a>
    <a href="<?= url('/') ?>" target="_blank" rel="noopener" class="inline-flex items-center h-9 px-4 rounded-lg border border-ink-300 text-sm font-medium hover:bg-sunken transition">View Website ↗</a>
  </div>
</div>
<?php endif; ?>

<script>
(function () {
  "use strict";
  var toggle = document.getElementById('customize-toggle');
  var panel = document.getElementById('customize-panel');
  var cancel = document.getElementById('customize-cancel');
  if (!toggle || !panel) return;
  var setOpen = function (open) {
    panel.classList.toggle('hidden', !open);
    toggle.setAttribute('aria-expanded', String(open));
  };
  toggle.addEventListener('click', function () { setOpen(panel.classList.contains('hidden')); });
  if (cancel) cancel.addEventListener('click', function () { setOpen(false); });
})();
</script>
