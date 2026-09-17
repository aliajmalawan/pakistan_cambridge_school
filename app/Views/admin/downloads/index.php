<div class="flex items-center justify-between gap-4 mb-5">
  <p class="text-sm text-ink-500">Files and forms shown on the public <a href="<?= url('/downloads') ?>" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">Downloads &amp; Forms</a> page.</p>
  <a href="<?= url('/admin/downloads/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add Download</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[760px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold">Title</th>
        <th class="px-4 py-3 font-semibold">Category</th>
        <th class="px-4 py-3 font-semibold">Source</th>
        <th class="px-4 py-3 font-semibold">Downloads</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php foreach ($downloads as $d): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3 font-medium"><?= e($d['title']) ?></td>
        <td class="px-4 py-3 text-ink-500"><?= e(\App\Models\Download::CATEGORIES[$d['category']] ?? $d['category']) ?></td>
        <td class="px-4 py-3 text-ink-500">
          <?php if (!empty($d['file_path'])): ?>
          <a href="<?= upload_url($d['file_path']) ?>" target="_blank" rel="noopener" class="text-navy hover:underline">File (<?= e(strtoupper($d['file_ext'] ?? '')) ?><?= $d['file_size'] ? ', ' . e(\App\Models\Download::humanSize((int) $d['file_size'])) : '' ?>)</a>
          <?php elseif (!empty($d['external_url'])): ?>
          <a href="<?= e($d['external_url']) ?>" target="_blank" rel="noopener" class="text-navy hover:underline">External link</a>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td class="px-4 py-3 tabular-nums text-ink-500"><?= (int) $d['download_count'] ?></td>
        <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $d['status']]); ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/downloads/edit/' . $d['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/downloads/delete/' . $d['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$downloads): ?>
      <tr><td colspan="6" class="px-4 py-12 text-center text-ink-500">No downloads yet — add a prospectus, admission form or fee challan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
