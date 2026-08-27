<div class="flex items-center justify-between gap-4 mb-5">
  <p class="text-sm text-ink-500">Profiles shown on the public Faculty page.</p>
  <a href="<?= url('/admin/faculty/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add Member</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[700px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold w-14">Order</th>
        <th class="px-4 py-3 font-semibold">Name</th>
        <th class="px-4 py-3 font-semibold">Designation</th>
        <th class="px-4 py-3 font-semibold">Department</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php foreach ($faculty as $m): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3 tabular-nums text-ink-500"><?= (int) $m['sort_order'] ?></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2.5">
            <?php if ($m['photo']): ?>
            <img src="<?= upload_url($m['photo']) ?>" alt="" class="w-8 h-8 rounded-full object-cover">
            <?php else: ?>
            <span class="w-8 h-8 rounded-full bg-navy-50 text-navy text-xs font-bold inline-flex items-center justify-center"><?= e(mb_substr($m['name'], 0, 1)) ?></span>
            <?php endif; ?>
            <span class="font-medium"><?= e($m['name']) ?></span>
          </div>
        </td>
        <td class="px-4 py-3 text-ink-500"><?= e($m['designation']) ?></td>
        <td class="px-4 py-3 text-ink-500"><?= e($m['department']) ?: '—' ?></td>
        <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $m['status']]); ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/faculty/edit/' . $m['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/faculty/delete/' . $m['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$faculty): ?>
      <tr><td colspan="6" class="px-4 py-12 text-center text-ink-500">No faculty profiles yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
