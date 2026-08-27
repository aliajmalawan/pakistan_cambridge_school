<?php
/**
 * Shared list screen for registry-driven admin modules (see Admin\SectionController).
 * expects: $base, $intro, $sections
 */
$cell = function (array $row, string $col, array $meta): string {
    $raw = (string) ($row[$col] ?? '');
    switch ($meta['format'] ?? 'text') {
        case 'date':
            if ($raw === '' || $raw === '0000-00-00') {
                return '<span class="text-ink-300">—</span>';
            }
            return '<span class="tabular-nums">' . e(date('j M Y', strtotime($raw))) . '</span>';
        case 'money':
            return '<span class="tabular-nums">Rs ' . e(number_format((int) $raw)) . '</span>';
        case 'badge':
            return $raw === ''
                ? '<span class="text-ink-300">—</span>'
                : '<span class="inline-flex items-center h-5 px-2 rounded-full bg-navy-50 text-navy text-[10px] font-bold uppercase tracking-wider">' . e($raw) . '</span>';
        case 'flag':
            return (int) $raw === 1
                ? '<span class="inline-flex items-center h-5 px-2 rounded-full bg-gold/15 text-gold-deep text-[10px] font-bold uppercase tracking-wider">' . e($meta['on'] ?? 'Yes') . '</span>'
                : '<span class="text-ink-300">—</span>';
        default:
            return $raw === '' ? '<span class="text-ink-300">—</span>' : e(excerpt($raw, 90));
    }
};
?>

<p class="text-sm text-ink-500 mb-6"><?= $intro /* trusted: written in the controller, carries links */ ?></p>

<div class="space-y-8">
  <?php foreach ($sections as $kind => $s): ?>
  <section class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-hidden">
    <div class="flex items-start justify-between gap-4 px-5 py-4 border-b border-ink-100 bg-sunken/50">
      <div>
        <h2 class="font-heading font-bold text-navy"><?= e($s['plural']) ?></h2>
        <p class="text-xs text-ink-500 mt-0.5"><?= e($s['blurb']) ?></p>
      </div>
      <a href="<?= url($base . '/' . $kind . '/create') ?>"
         class="inline-flex items-center h-9 px-4 rounded-lg bg-navy text-paper text-xs font-semibold hover:brightness-110 transition shrink-0">+ Add</a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm min-w-[640px]">
        <thead>
          <tr class="text-left text-[11px] uppercase tracking-wider text-ink-700 border-b border-ink-100">
            <?php if ($s['sortable'] ?? true): ?>
            <th scope="col" class="px-5 py-2.5 font-semibold w-14">Order</th>
            <?php endif; ?>
            <?php foreach ($s['columns'] as $meta): ?>
            <th scope="col" class="px-5 py-2.5 font-semibold"><?= e($meta['label']) ?></th>
            <?php endforeach; ?>
            <th scope="col" class="px-5 py-2.5 font-semibold">Status</th>
            <th scope="col" class="px-5 py-2.5 font-semibold text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-ink-100">
          <?php foreach ($s['rows'] as $row): ?>
          <tr class="hover:bg-sunken/40">
            <?php if ($s['sortable'] ?? true): ?>
            <td class="px-5 py-3 tabular-nums text-ink-500"><?= (int) $row['sort_order'] ?></td>
            <?php endif; ?>
            <?php $first = true; foreach ($s['columns'] as $col => $meta): ?>
            <td class="px-5 py-3 <?= $first ? 'font-medium' : 'text-ink-500' ?>"><?= $cell($row, $col, $meta) ?></td>
            <?php $first = false; endforeach; ?>
            <td class="px-5 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $row['status']]); ?></td>
            <td class="px-5 py-3 text-right whitespace-nowrap">
              <a href="<?= url($base . '/' . $kind . '/edit/' . $row['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
              <?php App\Core\View::partial('admin/partials/delete_button', ['action' => $base . '/' . $kind . '/delete/' . $row['id']]); ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (!$s['rows']): ?>
          <tr><td colspan="<?= count($s['columns']) + (($s['sortable'] ?? true) ? 3 : 2) ?>" class="px-5 py-10 text-center text-ink-500">Nothing here yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
  <?php endforeach; ?>
</div>
