<?php
use App\Core\Chart;

$card = function (string $title, ?string $note = null): string {
    $head = '<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">'
          . '<div class="flex items-center justify-between gap-3 mb-4">'
          . '<h2 class="font-semibold">' . e($title) . '</h2>';
    if ($note) {
        $head .= '<span class="text-xs text-ink-500 shrink-0">' . e($note) . '</span>';
    }
    return $head . '</div>';
};
$rangeNote = strtolower($ranges[$days]);
?>

<!-- Range selector -->
<div class="flex items-center justify-between gap-3 mb-5 flex-wrap">
  <div>
    <p class="text-sm text-ink-500">
      Website traffic and engagement.
      <?php if ($trackingSince): ?>
      Recording since <?= format_date($trackingSince, 'j F Y') ?>.
      <?php endif; ?>
    </p>
  </div>
  <div class="flex gap-2" role="group" aria-label="Reporting period">
    <?php foreach ($ranges as $value => $label): ?>
    <a href="<?= url('/admin/analytics?range=' . $value) ?>"
       class="inline-flex items-center h-9 px-4 rounded-full text-sm font-medium border transition
       <?= $days === $value ? 'bg-navy text-paper border-navy' : 'border-ink-300 text-ink-700 hover:bg-sunken' ?>"
       <?= $days === $value ? 'aria-current="true"' : '' ?>><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Headline figures -->
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
  <?php
  $figures = [
      ['Page Views',        number_format($totalViews),      $rangeNote],
      ['Unique Visitors',   number_format($uniqueVisitors),  $onlineNow . ' online right now'],
      ['Pages per Visitor', number_format($pagesPerVisitor, 1), 'how deep visitors browse'],
      ['Direct Traffic',    $directShare . '%',              'typed, bookmarked or shared privately'],
  ];
  foreach ($figures as [$label, $value, $sub]): ?>
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
    <div class="text-[11px] font-semibold uppercase tracking-wider text-ink-500"><?= e($label) ?></div>
    <div class="text-3xl font-bold tabular text-navy mt-1"><?= e($value) ?></div>
    <div class="text-xs text-ink-500 mt-1"><?= e($sub) ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Traffic over time -->
<div class="mt-6">
  <?= $card('Traffic & Visitors', $ranges[$days]) ?>
    <?= Chart::line(
        [
            ['label' => 'Page Views',      'color' => Chart::BLUE,   'data' => array_values($dailyViews)],
            ['label' => 'Unique Visitors', 'color' => Chart::ORANGE, 'data' => array_values($dailyUnique)],
        ],
        array_map(fn($d) => date('j M', strtotime($d)), array_keys($dailyViews)),
        'Daily page views and unique visitors over the selected period',
        'Visits are counted as people browse the public website. Figures appear once the site has had traffic.'
    ) ?>
    <p class="text-xs text-ink-500 mt-3 pt-3 border-t border-ink-100">
      Today: <strong class="text-ink-900 tabular"><?= number_format($viewsToday) ?></strong> views ·
      Yesterday: <strong class="text-ink-900 tabular"><?= number_format($viewsYesterday) ?></strong>
      <?= Chart::delta($viewsToday, $viewsYesterday) ?>
    </p>
  </div>
</div>

<!-- Pages + sources -->
<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div>
    <?= $card('Most Visited Pages', $ranges[$days]) ?>
      <?= Chart::bars($topPages, 'The pages visitors open most will be listed here once traffic arrives.') ?>
    </div>
  </div>
  <div>
    <?= $card('Traffic Sources', 'external referrers') ?>
      <?= Chart::bars($topReferrers, 'When someone reaches the site from Facebook, Google or another website, the source is listed here. Direct visits are counted in the figure above.', Chart::AQUA) ?>
    </div>
  </div>
</div>

<!-- Weekday pattern -->
<div class="mt-6">
  <?= $card('Busiest Days of the Week', $ranges[$days]) ?>
    <?= Chart::columns($byWeekday, 'Page views grouped by day of the week', 'Once the site has a week of traffic, the busiest days appear here.', Chart::BLUE) ?>
    <p class="text-xs text-ink-500 mt-3 pt-3 border-t border-ink-100">Useful for timing notices and announcements — publish when families are already visiting.</p>
  </div>
</div>

<!-- Admissions -->
<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div>
    <?= $card('Applications per Month', 'last 6 months') ?>
      <?= Chart::columns($admissionsMonthly, 'Admission applications received per month', 'Applications submitted through the online form are counted here each month.') ?>
    </div>
  </div>
  <div>
    <?= $card('Application Pipeline') ?>
      <?= Chart::donut([
          ['label' => 'Pending',      'value' => $statusSplit['pending'] ?? 0,      'color' => Chart::GOLD],
          ['label' => 'Under Review', 'value' => $statusSplit['under_review'] ?? 0, 'color' => Chart::BLUE],
          ['label' => 'Accepted',     'value' => $statusSplit['accepted'] ?? 0,     'color' => Chart::AQUA],
          ['label' => 'Rejected',     'value' => $statusSplit['rejected'] ?? 0,     'color' => Chart::RED],
      ], 'Admission applications by status', 'Applications', 'The pipeline fills as applications arrive and you move them through review.') ?>
      <?php if (($statusSplit['total'] ?? 0) > 0): ?>
      <p class="text-sm text-ink-500 mt-4 pt-4 border-t border-ink-100">
        Acceptance rate: <strong class="text-ink-900 tabular"><?= round(($statusSplit['accepted'] ?? 0) / $statusSplit['total'] * 100, 1) ?>%</strong> of all applications.
      </p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if ($classDemand): ?>
<div class="mt-6">
  <?= $card('Demand by Class', 'all applications') ?>
    <?= Chart::bars($classDemand, 'Class demand appears once applications are received.') ?>
  </div>
</div>
<?php endif; ?>

<!-- Messages + content -->
<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div>
    <?= $card('Contact Messages per Month', 'last 6 months') ?>
      <?= Chart::columns($messagesMonthly, 'Contact messages received per month', 'Messages sent through the contact form are counted here each month.', Chart::ORANGE) ?>
    </div>
  </div>
  <div>
    <?= $card('Content Published per Month', 'last 6 months') ?>
      <?= Chart::columns($contentMonthly, 'News, events and notices published per month', 'Publish a news post or notice and it will be counted here.', Chart::AQUA) ?>
    </div>
  </div>
</div>

<?php if ($isSuper): ?>
<!-- Admin activity -->
<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div>
    <?= $card('Admin Users by Role') ?>
      <?= Chart::donut([
          ['label' => 'Super Admin', 'value' => $usersByRole['superadmin'] ?? 0, 'color' => Chart::BLUE],
          ['label' => 'Admin',       'value' => $usersByRole['admin'] ?? 0,      'color' => Chart::AQUA],
          ['label' => 'Editor',      'value' => $usersByRole['editor'] ?? 0,     'color' => Chart::GOLD],
      ], 'Admin users grouped by role', 'Users', 'Add admin accounts and they will be grouped here by role.') ?>
    </div>
  </div>
  <div>
    <?= $card('Admin Sign-ins', 'last 14 days') ?>
      <?= Chart::columns($loginsDaily, 'Successful admin sign-ins per day', 'Sign-ins to this panel are recorded here.', Chart::BLUE) ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Privacy note -->
<div class="mt-6 rounded-xl border border-ink-100 bg-white shadow-kds-sm p-5 text-sm text-ink-500">
  <h2 class="font-semibold text-ink-900 mb-2">About these figures</h2>
  <ul class="space-y-1.5">
    <li>Visitors are counted by a one-way fingerprint. <span class="font-medium text-ink-700">No IP address is ever stored</span>, so a visitor can be counted but not identified.</li>
    <li>Search-engine crawlers, admin pages, images and stylesheets are excluded — these are real people reading real pages.</li>
    <li>A <span class="font-medium text-ink-700">unique visitor</span> is counted once per period no matter how many pages they open.</li>
    <li>Traffic sources only list sites outside the PCS website domain; moving between pages of this site is not a source.</li>
  </ul>
</div>
