<div class="flex items-center justify-between gap-4 mb-5">
  <p class="text-sm text-ink-500">
    Shown on the <a href="<?= url('/vision-mission') ?>" target="_blank" rel="noopener" class="text-navy font-semibold hover:underline">Vision &amp; Mission</a> page.
    The mission and vision statements themselves are edited in <a href="<?= url('/admin/settings') ?>" class="text-navy font-semibold hover:underline">Site Settings</a>.
  </p>
  <a href="<?= url('/admin/values/create') ?>" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Add Value</a>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[680px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th scope="col" class="px-4 py-3 font-semibold w-14">Order</th>
        <th scope="col" class="px-4 py-3 font-semibold">Value</th>
        <th scope="col" class="px-4 py-3 font-semibold">Colour</th>
        <th scope="col" class="px-4 py-3 font-semibold">Status</th>
        <th scope="col" class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php foreach ($values as $v): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3 tabular text-ink-500"><?= (int) $v['sort_order'] ?></td>
        <td class="px-4 py-3">
          <div class="flex items-start gap-3">
            <span class="w-9 h-9 rounded-lg shrink-0 inline-flex items-center justify-center text-white <?= e($v['accent']) ?>"
                  style="background:var(--accent)"><?= App\Core\Icon::svg($v['icon'], 17) ?></span>
            <div class="min-w-0">
              <div class="font-medium"><?= e($v['title']) ?></div>
              <div class="text-xs text-ink-500 mt-0.5"><?= e(excerpt($v['description'], 92)) ?></div>
            </div>
          </div>
        </td>
        <td class="px-4 py-3 text-ink-500"><?= e(App\Models\CoreValue::ACCENTS[$v['accent']] ?? $v['accent']) ?></td>
        <td class="px-4 py-3"><?php App\Core\View::partial('admin/partials/status_badge', ['status' => $v['status']]); ?></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/values/edit/' . $v['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/values/delete/' . $v['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$values): ?>
      <tr><td colspan="5" class="px-4 py-12 text-center text-ink-500">No core values yet — the section is hidden on the public page until you add one.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
