<?php $badges = ['pending' => 'bg-warning/15 text-warning', 'under_review' => 'bg-info/15 text-info', 'accepted' => 'bg-success/15 text-success', 'rejected' => 'bg-danger/15 text-danger']; ?>

<div class="flex items-center justify-between gap-4 mb-5 flex-wrap">
  <div class="flex gap-2 flex-wrap">
    <?php foreach (['' => 'All'] + App\Models\Admission::STATUSES as $key => $label): ?>
    <a href="<?= url('/admin/admissions' . ($key !== '' ? '?status=' . $key : '')) ?>"
       class="inline-flex items-center h-8 px-4 rounded-full text-sm font-medium border transition
       <?= $status === (string) $key ? 'bg-navy text-paper border-navy' : 'border-ink-300 text-ink-700 hover:bg-sunken' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>
  <form method="get" action="<?= url('/admin/admissions') ?>" class="flex gap-2">
    <?php if ($status): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?>
    <input name="q" value="<?= e($search) ?>" placeholder="Search name, app no, phone…"
           class="h-10 w-64 rounded-lg border border-ink-300 bg-white px-3 text-sm focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
    <button class="h-10 px-4 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">Search</button>
  </form>
</div>

<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[760px]">
    <thead>
      <tr class="bg-sunken text-left text-[11px] uppercase tracking-wider text-ink-700">
        <th class="px-4 py-3 font-semibold">App No</th>
        <th class="px-4 py-3 font-semibold">Student</th>
        <th class="px-4 py-3 font-semibold">Class</th>
        <th class="px-4 py-3 font-semibold">Guardian Phone</th>
        <th class="px-4 py-3 font-semibold">Submitted</th>
        <th class="px-4 py-3 font-semibold">Status</th>
        <th class="px-4 py-3 font-semibold text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-ink-100">
      <?php foreach ($paged['items'] as $a): ?>
      <tr class="hover:bg-sunken/40">
        <td class="px-4 py-3 tabular-nums font-medium text-navy"><?= e($a['app_no']) ?></td>
        <td class="px-4 py-3">
          <div class="font-medium"><?= e($a['student_name']) ?></div>
          <div class="text-xs text-ink-500">s/o <?= e($a['father_name']) ?></div>
        </td>
        <td class="px-4 py-3 text-ink-500"><?= e($a['class_applied']) ?></td>
        <td class="px-4 py-3 tabular-nums text-ink-500"><?= e($a['guardian_phone']) ?></td>
        <td class="px-4 py-3 text-ink-500 tabular-nums"><?= format_date($a['created_at'], 'j M Y') ?></td>
        <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide <?= $badges[$a['status']] ?>"><?= e(App\Models\Admission::STATUSES[$a['status']]) ?></span></td>
        <td class="px-4 py-3 text-right whitespace-nowrap">
          <a href="<?= url('/admin/admissions/show/' . $a['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Review</a>
          <a href="<?= url('/admin/admissions/edit/' . $a['id']) ?>" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition">Edit</a>
          <?php App\Core\View::partial('admin/partials/delete_button', ['action' => '/admin/admissions/delete/' . $a['id']]); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$paged['items']): ?>
      <tr><td colspan="7" class="px-4 py-12 text-center text-ink-500"><?= $search ? 'No applications match "' . e($search) . '".' : 'No applications in this view.' ?></td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="mt-5 flex items-center justify-between gap-4 flex-wrap">
  <?= pagination_links($paged, url('/admin/admissions?' . http_build_query(array_filter(['status' => $status, 'q' => $search])))) ?>
  <span class="text-xs text-ink-500 tabular-nums"><?= (int) $paged['total'] ?> applications</span>
</div>
