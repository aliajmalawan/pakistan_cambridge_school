<div class="flex items-center justify-between gap-4 mb-5 flex-wrap">
  <div class="flex gap-2">
    <?php
    $tabs = ['' => 'All'] + App\Models\News::CATEGORIES;
    foreach ($tabs as $key => $label):
        $active = $category === (string) $key;
    ?>
    <a href="<?= url('/admin/news' . ($key !== '' ? '?category=' . $key : '')) ?>"
       class="inline-flex items-center h-8 px-4 rounded-full text-sm font-medium border transition
       <?= $active ? 'bg-navy text-paper border-navy' : 'border-ink-300 text-ink-700 hover:bg-sunken' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>
  <a href="<?= url('/admin/news/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">+ Add Post</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[720px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold">Title</th>
        <th class="px-4 py-3 font-semibold">Category</th>
        <th class="px-4 py-3 font-semibold">Published</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php $catBadge = ['notice' => 'bg-warning/15 text-warning', 'event' => 'bg-info/15 text-info', 'news' => 'bg-success/15 text-success']; ?>
      <?php foreach ($paged['items'] as $n): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3">
          <div class="font-medium"><?= e($n['title']) ?></div>
          <a class="text-xs text-navy hover:underline" href="<?= url('/news/' . $n['slug']) ?>" target="_blank" rel="noopener">/news/<?= e($n['slug']) ?></a>
        </td>
        <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide <?= $catBadge[$n['category']] ?>"><?= e(App\Models\News::CATEGORIES[$n['category']]) ?></span></td>
        <td class="px-4 py-3 text-ink-500 tabular-nums"><?= format_date($n['published_at'], 'j M Y') ?></td>
        <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $n['status']]); ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/news/edit/' . $n['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/news/delete/' . $n['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$paged['items']): ?>
      <tr><td colspan="5" class="px-4 py-12 text-center text-ink-500">No posts in this category yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="mt-5 flex items-center justify-between gap-4 flex-wrap">
  <?= pagination_links($paged, url('/admin/news' . ($category ? '?category=' . $category : ''))) ?>
  <span class="text-xs text-ink-500 tabular-nums"><?= (int) $paged['total'] ?> posts total</span>
</div>
