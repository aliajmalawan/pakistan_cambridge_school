<div class="flex items-center justify-between gap-4 mb-5">
  <p class="text-sm text-ink-500">Quotes shown in the "What Families Say" section of the homepage (first three published).</p>
  <a href="<?= url('/admin/testimonials/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add Testimonial</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[640px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold w-14">Order</th>
        <th class="px-4 py-3 font-semibold">Name</th>
        <th class="px-4 py-3 font-semibold">Quote</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php foreach ($testimonials as $t): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3 tabular-nums text-ink-500"><?= (int) $t['sort_order'] ?></td>
        <td class="px-4 py-3">
          <div class="font-medium"><?= e($t['name']) ?></div>
          <div class="text-xs text-ink-500"><?= e($t['role']) ?></div>
        </td>
        <td class="px-4 py-3 text-ink-500 max-w-md"><?= e(excerpt($t['content'], 90)) ?></td>
        <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $t['status']]); ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/testimonials/edit/' . $t['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/testimonials/delete/' . $t['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$testimonials): ?>
      <tr><td colspan="5" class="px-4 py-12 text-center text-ink-500">No testimonials yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
