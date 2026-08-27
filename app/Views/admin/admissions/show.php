<?php
use App\Core\Icon;
use App\Models\Admission;

$badges = [
    'pending'      => 'bg-warning/15 text-warning',
    'under_review' => 'bg-info/15 text-info',
    'accepted'     => 'bg-success/15 text-success',
    'rejected'     => 'bg-danger/15 text-danger',
];

$phoneRaw = preg_replace('/[^0-9+]/', '', $application['guardian_phone']);
$whatsapp = preg_replace('/[^0-9]/', '', $application['guardian_phone']);
$age = (int) ((time() - strtotime($application['dob'])) / 31557600);

// The same four groups the guardian filled on the public form, in the same
// order — the office reads the application the way it was written.
$groups = [
    ['1', 'accent-blue', 'Student details', [
        ["Student's name", $application['student_name']],
        ["Father's name",  $application['father_name']],
        ['B-Form / CNIC',  $application['bform'], 'tabular-nums'],
        ['Date of birth',  format_date($application['dob']) . ' (' . $age . ' years)'],
        ['Gender',         ucfirst($application['gender'])],
    ]],
    ['2', 'accent-teal', 'Applying for', [
        ['Class',           $application['class_applied']],
        ['Previous school', $application['prev_school'] ?: '—'],
    ]],
    ['3', 'accent-violet', 'Guardian & home', [
        ['Guardian phone', $application['guardian_phone'], 'tabular-nums'],
        ['Home address',   $application['address']],
    ]],
];

$flow = ['pending', 'under_review', 'accepted'];
$atIndex = array_search($application['status'], $flow, true);
?>

<a href="<?= url('/admin/admissions') ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy hover:underline">&larr; All applications</a>

<div class="mt-4 grid lg:grid-cols-[1fr_320px] gap-5 items-start">

  <!-- ============ The application ============ -->
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-hidden">
    <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-ink-100 flex-wrap bg-sunken/40">
      <div>
        <h2 class="font-heading font-bold text-xl text-navy"><?= e($application['student_name']) ?></h2>
        <div class="text-sm text-ink-500 mt-1">
          <span class="tabular-nums font-semibold text-ink-700"><?= e($application['app_no']) ?></span>
          &middot; submitted <?= format_date($application['created_at'], 'j M Y, g:i A') ?>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide <?= $badges[$application['status']] ?>">
          <?= e(Admission::STATUSES[$application['status']]) ?>
        </span>
        <a href="<?= url('/admin/admissions/edit/' . $application['id']) ?>"
           class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg border border-ink-100 bg-white text-xs font-semibold text-navy hover:border-navy transition">
          <?= Icon::svg('pencil', 14) ?> Edit details
        </a>
      </div>
    </div>

    <?php foreach ($groups as [$no, $accent, $title, $rows]): ?>
    <section class="px-6 py-5 border-b border-ink-100">
      <h3 class="flex items-center gap-2.5 font-heading font-bold text-navy mb-4">
        <span class="<?= e($accent) ?> inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-lg text-[11px] font-bold text-white tabular-nums"
              style="background:var(--accent)"><?= $no ?></span>
        <?= e($title) ?>
      </h3>
      <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
        <?php foreach ($rows as $row): ?>
        <div<?= $row[0] === 'Home address' ? ' class="sm:col-span-2"' : '' ?>>
          <dt class="text-[11px] font-semibold uppercase tracking-wider text-ink-300"><?= e($row[0]) ?></dt>
          <dd class="mt-0.5 text-ink-900 <?= e($row[2] ?? '') ?>"><?= e($row[1]) ?></dd>
        </div>
        <?php endforeach; ?>
      </dl>
    </section>
    <?php endforeach; ?>

    <section class="px-6 py-5">
      <h3 class="flex items-center gap-2.5 font-heading font-bold text-navy mb-4">
        <span class="accent-amber inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-lg text-[11px] font-bold text-white"
              style="background:var(--accent)">4</span>
        Anything else
      </h3>
      <?php if ($application['notes']): ?>
      <p class="text-sm text-ink-700 leading-relaxed"><?= e($application['notes']) ?></p>
      <?php else: ?>
      <p class="text-sm text-ink-300">Nothing was added.</p>
      <?php endif; ?>
    </section>
  </div>

  <!-- ============ Actions ============ -->
  <aside class="flex flex-col gap-5">

    <!-- Where this application has got to -->
    <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
      <h3 class="font-heading font-bold text-navy text-sm mb-4">Progress</h3>
      <?php if ($application['status'] === 'rejected'): ?>
      <p class="flex items-center gap-2.5 text-sm font-semibold text-danger">
        <span class="w-6 h-6 rounded-full bg-danger/15 inline-flex items-center justify-center"><?= Icon::svg('check', 13) ?></span>
        Rejected
      </p>
      <p class="text-xs text-ink-500 mt-2">Set the status back to Pending if this was a mistake.</p>
      <?php else: ?>
      <ol class="flex flex-col gap-0">
        <?php foreach ($flow as $i => $key): ?>
        <?php $done = $atIndex !== false && $i <= $atIndex; $isNow = $i === $atIndex; ?>
        <li class="flex gap-3 <?= $i < count($flow) - 1 ? 'pb-4' : '' ?> relative">
          <?php if ($i < count($flow) - 1): ?>
          <span class="absolute left-[11px] top-6 bottom-0 w-0.5 <?= $done && $i < $atIndex ? 'bg-success' : 'bg-ink-100' ?>"></span>
          <?php endif; ?>
          <span class="relative w-6 h-6 rounded-full inline-flex items-center justify-center shrink-0 text-[10px] font-bold
                       <?= $done ? 'bg-success text-white' : 'bg-sunken text-ink-300 border border-ink-100' ?>">
            <?= $done ? Icon::svg('check', 13) : $i + 1 ?>
          </span>
          <span class="text-sm <?= $isNow ? 'font-semibold text-navy' : ($done ? 'text-ink-700' : 'text-ink-300') ?>">
            <?= e(Admission::STATUSES[$key]) ?>
          </span>
        </li>
        <?php endforeach; ?>
      </ol>
      <?php endif; ?>
    </div>

    <!-- Change the status -->
    <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
      <h3 class="font-heading font-bold text-navy text-sm mb-3">Decision</h3>
      <form method="post" action="<?= url('/admin/admissions/status/' . $application['id']) ?>" class="flex flex-col gap-3">
        <?= App\Core\Csrf::field() ?>
        <label for="status" class="sr-only">Set status</label>
        <select id="status" name="status"
                class="h-11 rounded-lg border border-ink-100 bg-sunken px-3 text-sm focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
          <?php foreach (Admission::STATUSES as $key => $label): ?>
          <option value="<?= e($key) ?>" <?= $application['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="inline-flex items-center justify-center h-11 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">
          Update status
        </button>
      </form>
      <p class="text-xs text-ink-500 mt-3">The guardian is not emailed automatically — call or SMS them after deciding.</p>
    </div>

    <!-- Reach the guardian -->
    <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
      <h3 class="font-heading font-bold text-navy text-sm mb-3">Contact the guardian</h3>
      <div class="flex flex-col gap-2.5">
        <a href="tel:<?= e($phoneRaw) ?>"
           class="inline-flex items-center gap-2.5 h-11 px-4 rounded-lg border border-ink-100 bg-sunken text-sm font-semibold text-navy hover:border-navy transition">
          <?= Icon::svg('phone', 16) ?> <?= e($application['guardian_phone']) ?>
        </a>
        <?php if ($whatsapp !== ''): ?>
        <a href="https://wa.me/<?= e($whatsapp) ?>" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2.5 h-11 px-4 rounded-lg border border-ink-100 bg-sunken text-sm font-semibold text-navy hover:border-navy transition">
          <?= Icon::svg('chat', 16) ?> WhatsApp
        </a>
        <?php endif; ?>
      </div>
    </div>
  </aside>
</div>
