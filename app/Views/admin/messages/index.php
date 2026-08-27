<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm divide-y divide-ink-100">
  <?php foreach ($paged['items'] as $m): ?>
  <a href="<?= url('/admin/messages/show/' . $m['id']) ?>" class="flex items-center gap-4 px-5 py-4 hover:bg-sunken/50 transition <?= $m['is_read'] ? '' : 'bg-navy-50/40' ?>">
    <span class="w-2 h-2 rounded-full shrink-0 <?= $m['is_read'] ? 'bg-transparent' : 'bg-navy' ?>" aria-hidden="true"></span>
    <div class="min-w-0 grow">
      <div class="flex items-center gap-2">
        <span class="text-sm <?= $m['is_read'] ? 'font-medium' : 'font-bold' ?> truncate"><?= e($m['subject']) ?></span>
      </div>
      <div class="text-xs text-ink-500 mt-0.5 truncate"><?= e($m['name']) ?><?= $m['email'] ? ' · ' . e($m['email']) : '' ?><?= $m['phone'] ? ' · ' . e($m['phone']) : '' ?></div>
    </div>
    <time class="text-xs text-ink-500 tabular-nums shrink-0"><?= time_ago($m['created_at']) ?></time>
  </a>
  <?php endforeach; ?>
  <?php if (!$paged['items']): ?>
  <div class="px-5 py-16 text-center text-sm text-ink-500">No contact messages yet — submissions from the public contact form appear here.</div>
  <?php endif; ?>
</div>

<div class="mt-5 flex items-center justify-between gap-4 flex-wrap">
  <?= pagination_links($paged, url('/admin/messages')) ?>
  <span class="text-xs text-ink-500 tabular-nums"><?= (int) $paged['total'] ?> messages</span>
</div>
