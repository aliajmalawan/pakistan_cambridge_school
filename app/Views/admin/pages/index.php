<div class="flex items-center justify-between gap-4 mb-5 flex-wrap">
  <p class="text-sm text-ink-500 max-w-2xl">
    Every public page on the website. <span class="font-medium text-ink-700">Content pages</span> are written here from scratch;
    <span class="font-medium text-ink-700">system pages</span> are built from live data, so their heading, intro and SEO text are editable but the page itself cannot be deleted.
  </p>
  <a href="<?= url('/admin/pages/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add Content Page</a>
</div>

<?php
$groups = [
    ['Content pages', 'You wrote these. Full HTML, own URL, deletable.', $contentPages, true],
    ['System pages',  'Rendered from live data by a route. Heading, intro and SEO are yours to edit.', $systemPages, false],
];
foreach ($groups as [$heading, $note, $rows, $deletable]):
?>
<section class="mb-8">
  <h2 class="font-semibold text-ink-900"><?= e($heading) ?> <span class="tabular text-ink-500 font-normal">(<?= count($rows) ?>)</span></h2>
  <p class="text-xs text-ink-500 mt-0.5 mb-3"><?= e($note) ?></p>

  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
    <table class="w-full text-sm min-w-[680px]">
      <thead>
        <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
          <th scope="col" class="px-4 py-3 font-semibold">Title</th>
          <th scope="col" class="px-4 py-3 font-semibold">URL</th>
          <th scope="col" class="px-4 py-3 font-semibold">Status</th>
          <th scope="col" class="px-4 py-3 font-semibold">Updated</th>
          <th scope="col" class="px-4 py-3 font-semibold text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-ink-100">
        <?php foreach ($rows as $p): ?>
        <?php $path = $deletable ? '/page/' . $p['slug'] : $p['route']; ?>
        <tr class="hover:bg-sunken/40">
          <td class="px-4 py-3">
            <div class="font-medium"><?= e($p['title']) ?></div>
            <?php if ($p['lede']): ?>
            <div class="text-xs text-ink-500 mt-0.5"><?= e(excerpt($p['lede'], 84)) ?></div>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3">
            <a class="text-navy hover:underline" href="<?= url($path) ?>" target="_blank" rel="noopener"><?= e($path) ?></a>
          </td>
          <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $p['status']]); ?></td>
          <td class="px-4 py-3 text-ink-500 tabular"><?= format_date($p['updated_at'], 'j M Y') ?></td>
          <td class="px-4 py-3 text-right whitespace-nowrap">
            <a href="<?= url('/admin/pages/edit/' . $p['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
            <?php if ($deletable): ?>
            <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/pages/delete/' . $p['id'], 'confirm' => 'Delete this page? Links to it will stop working.']); ?>
            <?php else: ?>
            <span class="inline-flex items-center h-8 px-3 text-xs text-ink-300" title="A route depends on this page">Built in</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
        <tr><td colspan="5" class="px-4 py-10 text-center text-ink-500">Nothing here yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
<?php endforeach; ?>
