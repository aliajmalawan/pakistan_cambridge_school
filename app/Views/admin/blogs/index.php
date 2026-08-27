<div class="flex items-center justify-between gap-4 mb-5 flex-wrap">
  <div></div>
  <a href="<?= url('/admin/blogs/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">+ Add Blog Post</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[720px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold">Title</th>
        <th class="px-4 py-3 font-semibold">Author</th>
        <th class="px-4 py-3 font-semibold">Published</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php foreach ($paged['items'] as $b): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3">
          <div class="font-medium"><?= e($b['title']) ?></div>
          <a class="text-xs text-navy hover:underline" href="<?= url('/blogs/' . $b['slug']) ?>" target="_blank" rel="noopener">/blogs/<?= e($b['slug']) ?></a>
        </td>
        <td class="px-4 py-3 text-ink-500"><?= e($b['author']) ?></td>
        <td class="px-4 py-3 text-ink-500 tabular-nums"><?= format_date($b['published_at'], 'j M Y') ?></td>
        <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $b['status']]); ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/blogs/edit/' . $b['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/blogs/delete/' . $b['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$paged['items']): ?>
      <tr><td colspan="5" class="px-4 py-12 text-center text-ink-500">No blog posts yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="mt-5 flex items-center justify-between gap-4 flex-wrap">
  <?= pagination_links($paged, url('/admin/blogs')) ?>
  <span class="text-xs text-ink-500 tabular-nums"><?= (int) $paged['total'] ?> posts total</span>
</div>
