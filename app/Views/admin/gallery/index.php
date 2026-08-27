<div class="flex items-center justify-between gap-4 mb-5">
  <p class="text-sm text-ink-500">Albums shown on the public Gallery page. Open an album to upload photos.</p>
  <a href="<?= url('/admin/gallery/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add Album</a>
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
  <?php foreach ($albums as $a): ?>
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-hidden">
    <a href="<?= url('/admin/gallery/edit/' . $a['id']) ?>" class="block relative aspect-video bg-navy">
      <?php if ($a['cover']): ?>
      <img src="<?= upload_url($a['cover']) ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
      <?php else: ?>
      <div class="absolute inset-0 bg-gradient-to-br from-navy-700 to-navy-950 flex items-center justify-center">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#C7962C" stroke-width="1.2" opacity="0.6"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="M4 17l5-4 3 2.5L16 11l4 4"/></svg>
      </div>
      <?php endif; ?>
    </a>
    <div class="p-4">
      <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
          <div class="font-medium truncate"><?= e($a['title']) ?></div>
          <div class="text-xs text-ink-500 mt-0.5 tabular-nums"><?= (int) $a['image_count'] ?> photos</div>
        </div>
        <?php App\Core\View::partial('admin/partials/status_badge', ['status' => $a['status']]); ?>
      </div>
      <div class="mt-3 flex gap-2">
        <a href="<?= url('/admin/gallery/edit/' . $a['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold bg-navy text-paper hover:brightness-110 transition">Manage Photos</a>
        <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/gallery/delete/' . $a['id'], 'confirm' => 'Delete this album AND all its photos permanently?']); ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if (!$albums): ?>
<div class="text-center py-16 border-[1.5px] border-dashed border-ink-300 rounded-xl bg-white">
  <p class="font-heading font-semibold text-lg">No albums yet</p>
  <p class="text-sm text-ink-500 mt-1">Create the first album, then upload photos into it.</p>
</div>
<?php endif; ?>
