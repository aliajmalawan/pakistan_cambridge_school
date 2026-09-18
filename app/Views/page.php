<?php
$title = $page['title'] ?? 'Pakistan Cambridge School';
$lede = $page['lede'] ?? '';
$body = $page['content'] ?? '';
$routeKey = $route ?? '';
?>

<section class="inner-hero">
    <div class="inner-hero-shape inner-hero-shape-a"></div>
    <div class="inner-hero-shape inner-hero-shape-b"></div>
    <div class="container inner-hero-grid">
        <div>
            <span class="eyebrow light"><i></i>Pakistan Cambridge School • Hafizabad</span>
            <h1><?= e($title) ?></h1>
            <?php if ($lede): ?><p><?= e($lede) ?></p><?php endif; ?>
        </div>
    </div>
</section>

<div class="page-breadcrumb">
    <div class="container"><a href="<?= url('/') ?>">Home</a><span>›</span><strong><?= e($title) ?></strong></div>
</div>

<section class="section-pad inner-page">
    <div class="container">
        <?php if ($routeKey === '/vision-mission'): ?>
            <div class="section-row" data-reveal><div><span class="eyebrow dark"><i></i>Our direction</span><h2>A clear purpose. <em>A higher standard.</em></h2></div><p>Pakistan Cambridge School builds confident learners who combine academic strength with character, communication and leadership.</p></div>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>

            <?php $mission = setting('mission'); $vision = setting('vision'); ?>
            <?php if ($mission || $vision): ?>
            <div class="statement-grid" data-reveal-group>
                <?php if ($mission): ?><article class="statement-card accent-mission"><div class="value-icon"><?= icon('send', 24) ?></div><span class="statement-label">Our Mission</span><p><?= e($mission) ?></p></article><?php endif; ?>
                <?php if ($vision): ?><article class="statement-card accent-vision"><div class="value-icon"><?= icon('sun', 24) ?></div><span class="statement-label">Our Vision</span><p><?= e($vision) ?></p></article><?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($values): ?>
            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>What defines us</span><h2>Our core <em>values.</em></h2></div></div>
            <div class="value-grid inner-value-grid" data-reveal-group>
                <?php foreach ($values as $i => $v): ?><article class="value-card"><span class="value-index">0<?= $i+1 ?></span><div class="value-icon"><?= icon($v['icon'] ?? 'book', 22) ?></div><h3><?= e($v['title'] ?? '') ?></h3><p><?= e($v['description'] ?? '') ?></p></article><?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if ($leadership): ?><div class="inner-callout" data-reveal><div><span class="eyebrow light"><i></i>Leadership</span><h3>People who keep the standard high.</h3></div><a class="btn btn-white" href="<?= url('/leadership') ?>">Meet the leadership →</a></div><?php endif; ?>

        <?php elseif ($routeKey === '/about'): ?>
            <?php if ($body): ?>
            <div class="split-grid" style="margin-bottom:70px">
                <div class="rich-content" data-reveal><?= $body ?></div>
                <div class="intro-panel" data-reveal>
                    <div class="panel-number">01</div>
                    <h3>A campus built around every learner</h3>
                    <p>From their first day of Playgroup to their final board exams, students are known, supported and pushed to grow.</p>
                    <div class="mini-stats">
                        <div><strong>01</strong><span>Playgroup to<br>Higher Secondary</span></div>
                        <div><strong>02</strong><span>Structured<br>curriculum</span></div>
                        <div><strong>03</strong><span>Every learner<br>known by name</span></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php $mission = setting('mission'); $vision = setting('vision'); ?>
            <?php if ($mission || $vision): ?>
            <div class="note-grid" data-reveal-group style="margin-bottom:60px">
                <?php if ($mission): ?><article class="note-card"><div class="value-icon" style="margin-bottom:18px"><?= icon('send', 22) ?></div><span>01</span><h3>Our Mission</h3><div class="rich-content"><p><?= e($mission) ?></p></div></article><?php endif; ?>
                <?php if ($vision): ?><article class="note-card"><div class="value-icon" style="margin-bottom:18px"><?= icon('sun', 22) ?></div><span>02</span><h3>Our Vision</h3><div class="rich-content"><p><?= e($vision) ?></p></div></article><?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($values): ?>
            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>What defines us</span><h2>Our core <em>values.</em></h2></div></div>
            <div class="value-grid inner-value-grid" data-reveal-group style="margin-bottom:60px">
                <?php foreach ($values as $i => $v): ?><article class="value-card"><span class="value-index">0<?= $i+1 ?></span><div class="value-icon"><?= icon($v['icon'] ?? 'book', 22) ?></div><h3><?= e($v['title'] ?? '') ?></h3><p><?= e($v['description'] ?? '') ?></p></article><?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($milestones): ?>
            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>Our journey</span><h2>Milestones <em>along the way.</em></h2></div></div>
            <div class="milestone-list" data-reveal-group style="margin-bottom:60px">
                <?php foreach ($milestones as $ms): ?><article class="milestone-item"><div class="milestone-year"><?= e($ms['year']) ?></div><div><h3><?= e($ms['title']) ?></h3><p><?= e($ms['detail'] ?? '') ?></p></div></article><?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($leadership): ?>
            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>Leadership</span><h2>A message from <em>our leaders.</em></h2></div><a class="btn btn-ghost" href="<?= url('/leadership') ?>">Meet the full team →</a></div>
            <div class="people-grid inner-people-grid people-grid-2" data-reveal-group>
                <?php foreach ($leadership as $person): ?><article class="person-card"><?php if (!empty($person['photo'])): ?><img src="<?= e(upload_url($person['photo'])) ?>" alt="<?= e($person['name']) ?>"><?php else: ?><div class="person-placeholder"><?= icon('user', 40) ?></div><?php endif; ?><div><span><?= e($person['designation']) ?></span><h3><?= e($person['name']) ?></h3><?php if (!empty($person['message'])): ?><div class="person-bio">"<?= e($person['message']) ?>"</div><?php endif; ?></div></article><?php endforeach; ?>
            </div>
            <?php endif; ?>

        <?php elseif ($routeKey === '/leadership'): ?>
            <div class="section-row" data-reveal><div><span class="eyebrow dark"><i></i>Our leadership</span><h2>People who keep the <em>standard high.</em></h2></div><p>Every policy, every classroom decision and every result at Pakistan Cambridge School runs through this team.</p></div>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>
            <div class="leaders-list" data-reveal-group>
                <?php foreach ($leadership as $person): ?>
                    <article class="leader-row">
                        <div class="leader-photo-col">
                            <div class="leader-photo-ring"><?php if (!empty($person['photo'])): ?><img src="<?= e(upload_url($person['photo'])) ?>" alt="<?= e($person['name']) ?>"><?php else: ?><div class="person-placeholder"><?= icon('user', 48) ?></div><?php endif; ?></div>
                            <span class="leader-photo-role"><?= e($person['designation']) ?></span>
                            <h3><?= e($person['name']) ?></h3>
                            <?php if (!empty($person['qualification'])): ?><p class="leader-qualification"><?= e($person['qualification']) ?></p><?php endif; ?>
                        </div>
                        <div class="leader-quote-col">
                            <span class="leader-quote-mark">&ldquo;</span>
                            <?php if (!empty($person['message'])): ?><div class="leader-message"><?= nl2br(e($person['message'])) ?></div><?php endif; ?>
                            <div class="leader-sign"><strong><?= e($person['name']) ?></strong><span><?= e($person['designation']) ?></span></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

        <?php elseif ($routeKey === '/academics'): ?>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>
            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>Academic framework</span><h2>How learning <em>works here.</em></h2></div><p>Clear expectations, structured assessment and a curriculum designed to build strong foundations before specialisation.</p></div>
            <div class="framework-grid" data-reveal-group>
                <?php foreach ($academicFramework as $i => $item): ?><article class="framework-card"><div class="value-icon"><?= icon($item['icon'] ?? 'book', 22) ?></div><span class="framework-no">0<?= $i + 1 ?></span><h3><?= e($item['title'] ?? '') ?></h3><p><?= e($item['body'] ?? '') ?></p></article><?php endforeach; ?>
            </div>
            <?php if ($gradingScale): ?><div class="table-card" data-reveal><div class="table-card-head"><h3>Grading scale</h3><span>Published by PCS</span></div><div class="responsive-table"><table><thead><tr><th>Grade</th><th>Band</th><th>Remark</th></tr></thead><tbody><?php foreach ($gradingScale as $g): ?><tr><td><?= e($g['grade']) ?></td><td><?= e($g['band']) ?></td><td><?= e($g['remark']) ?></td></tr><?php endforeach; ?></tbody></table></div></div><?php endif; ?>

        <?php elseif ($routeKey === '/academic-calendar'): $CE = \App\Models\CalendarEvent::class; ?>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>
            <div class="calendar-header" data-reveal><div><span class="eyebrow dark"><i></i>Session calendar</span><h2><?= e($calendarSession ?? 'Academic Session') ?></h2></div><a class="btn btn-ghost" href="<?= url('/downloads') ?>">Downloads & Forms →</a></div>

            <?php if (!$calendarSession): ?>
                <div class="empty-state">No calendar has been published yet.</div>
            <?php else: ?>

            <div class="cal-summary" data-reveal-group>
                <?php if ($calendarSpan): ?><div class="cal-stat span-stat"><strong><?= e($calendarSpan) ?></strong><span>Session span</span></div><?php endif; ?>
                <?php foreach ($CE::TYPES as $typeKey => $typeLabel): $n = $calendarTypeCounts[$typeKey] ?? 0; if (!$n) continue; ?>
                <div class="cal-stat"><strong><span class="cal-dot <?= $CE::typeClass($typeKey) ?>"></span><?= $n ?></strong><span><?= e($typeLabel) ?><?= $n > 1 ? 's' : '' ?></span></div>
                <?php endforeach; ?>
            </div>

            <?php if ($calendarUpcoming): ?>
            <div class="cal-upcoming" data-reveal>
                <h3 style="color:var(--navy);margin:0 0 18px">Coming up next</h3>
                <div class="cal-upcoming-grid">
                    <?php foreach ($calendarUpcoming as $event): $tc = $CE::typeClass($event['type']); ?>
                    <article class="cal-upcoming-card <?= $tc ?>">
                        <b><?= e($CE::TYPES[$event['type']] ?? ucfirst($event['type'])) ?></b>
                        <h4><?= e($event['title']) ?></h4>
                        <p><?= e($CE::dateLabel($event)) ?></p>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($academicCalendar): ?>
            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>Shape of the year</span><h2>Terms <em>&amp; breaks.</em></h2></div></div>
            <div class="calendar-list" data-reveal-group style="margin-bottom:65px">
                <?php foreach ($academicCalendar as $term): ?>
                <article class="calendar-item <?= !empty($term['milestone']) ? 'cal-exam' : 'cal-event' ?>">
                    <div class="calendar-date"><strong><?= !empty($term['milestone']) ? '★' : '—' ?></strong><span><?= !empty($term['milestone']) ? 'Exams' : 'Term' ?></span></div>
                    <div><span class="calendar-type"><?= e($term['period'] ?? '') ?></span><h3><?= e($term['term']) ?></h3><p><?= e(public_detail($term['detail'] ?? '')) ?></p></div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="section-row inner-section-row" data-reveal><div><span class="eyebrow dark"><i></i>Dated events</span><h2>Full <em>calendar.</em></h2></div></div>
            <?php if (!$calendarEvents): ?>
                <div class="empty-state">No dated events have been published for this session yet.</div>
            <?php else: ?>
                <?php foreach ($calendarByMonth as $monthKey => $monthEvents): ?>
                <div class="cal-month-group" data-reveal>
                    <h4 class="cal-month-label"><?= e(date('F Y', strtotime($monthKey . '-01'))) ?></h4>
                    <div class="calendar-list">
                        <?php foreach ($monthEvents as $event): $tc = $CE::typeClass($event['type']); ?>
                        <article class="calendar-item <?= $tc ?><?= $CE::isPast($event) ? ' is-past' : '' ?><?= $CE::isCurrent($event) ? ' is-current' : '' ?>">
                            <div class="calendar-date"><strong><?= e(date('d', strtotime($event['starts_on']))) ?></strong><span><?= e(date('D', strtotime($event['starts_on']))) ?></span></div>
                            <div>
                                <span class="cal-type-badge <?= $tc ?>"><?= e($CE::TYPES[$event['type']] ?? ucfirst($event['type'])) ?></span>
                                <h3><?= e($event['title']) ?></h3>
                                <?php if (!empty($event['ends_on']) && $event['ends_on'] !== $event['starts_on']): ?><p class="cal-daterange"><?= e($CE::dateLabel($event)) ?> · <?= $CE::days($event) ?> days</p><?php endif; ?>
                                <p><?= e(public_detail($event['detail'] ?? '')) ?></p>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="cal-legend" data-reveal>
                    <?php foreach ($CE::LEGEND as $class => $label): ?><span><span class="cal-dot <?= $class ?>"></span><?= e($label) ?></span><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php endif; ?>

        <?php elseif ($routeKey === '/programs'): ?>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>
            <div class="program-grid inner-program-grid" data-reveal-group><?php foreach ($programs as $i => $p): ?><article class="program-card"><div class="program-top"><span class="program-number">0<?= $i+1 ?></span><span class="program-icon"><?= icon($p['icon'] ?? 'book', 22) ?></span></div><span class="program-level"><?= e($p['level']) ?></span><h3><?= e($p['name']) ?></h3><p><?= e($p['description']) ?></p><?php if (!empty($p['subjects'])): ?><div class="subjects"><b>Subjects</b><span><?= e($p['subjects']) ?></span></div><?php endif; ?></article><?php endforeach; ?></div>

        <?php elseif ($routeKey === '/fees'): ?>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>
            <div class="table-card" data-reveal>
                <div class="table-card-head"><div><span class="eyebrow dark"><i></i>Transparent fees</span><h3>Published fee structure</h3></div><span>All amounts in PKR</span></div>
                <?php if ($feeStructure): ?>
                <div class="responsive-table"><table><thead><tr><th>Class / Group</th><th>Admission</th><th>Monthly</th><th>Exam</th><th>Notes</th></tr></thead><tbody><?php foreach ($feeStructure as $f): ?><tr><td><strong><?= e($f['class_group']) ?></strong></td><td><?= e(number_format((float)$f['admission_fee'])) ?></td><td><?= e(number_format((float)$f['monthly_fee'])) ?></td><td><?= e(number_format((float)$f['exam_fee'])) ?></td><td><?= e($f['notes'] ?? '') ?></td></tr><?php endforeach; ?></tbody></table></div>
                <?php else: ?>
                <div class="fees-empty">
                    <div class="fees-empty-icon"><?= icon('clock', 26) ?></div>
                    <h4>Fee structure not published yet</h4>
                    <p>The office is finalising rates for this session. Please contact admissions for current figures in the meantime.</p>
                    <a class="btn btn-ghost" href="<?= url('/contact') ?>">Contact Admissions →</a>
                </div>
                <?php endif; ?>
            </div>
            <div class="note-grid" data-reveal-group>
                <article class="note-card"><div class="value-icon"><?= icon('shield', 22) ?></div><span>01</span><h3>Concessions</h3><?php if ($feeConcession): foreach ($feeConcession as $n): ?><div class="rich-content"><?= $n['body'] ?></div><?php endforeach; else: ?><p class="subjects-empty">To be announced — contact the office for details.</p><?php endif; ?></article>
                <article class="note-card"><div class="value-icon"><?= icon('clock', 22) ?></div><span>02</span><h3>Payment</h3><?php if ($feePayment): foreach ($feePayment as $n): ?><div class="rich-content"><?= $n['body'] ?></div><?php endforeach; else: ?><p class="subjects-empty">To be announced — contact the office for details.</p><?php endif; ?></article>
            </div>

        <?php elseif ($routeKey === '/faculty'): ?>
            <?php if ($body): ?><div class="rich-content intro-copy"><?= $body ?></div><?php endif; ?>
            <div class="people-grid inner-people-grid"><?php foreach ($faculty as $person): ?><article class="person-card"><?php if (!empty($person['photo'])): ?><img src="<?= e(upload_url($person['photo'])) ?>" alt="<?= e($person['name']) ?>"><?php else: ?><div class="person-placeholder"><?= icon('user', 40) ?></div><?php endif; ?><div><span><?= e($person['designation']) ?></span><h3><?= e($person['name']) ?></h3><?php if (!empty($person['qualification']) && !str_starts_with($person['qualification'], 'Placeholder card')): ?><p><?= e($person['qualification']) ?></p><?php endif; ?><small><?= e($person['department']) ?></small><?php if (!empty($person['bio'])): ?><div class="person-bio"><?= e($person['bio']) ?></div><?php endif; ?></div></article><?php endforeach; ?></div>

        <?php elseif ($routeKey === '/news'): ?>
            <?php if ($news): ?>
            <div class="news-grid inner-news-grid"><?php foreach ($news as $item): ?><article class="news-card"><?php if (!empty($item['image'])): ?><img src="<?= e(upload_url($item['image'])) ?>" alt="<?= e($item['title']) ?>"><?php else: ?><div class="news-placeholder"><span><?= e(strtoupper(substr($item['category'] ?? 'NEWS', 0, 1))) ?></span></div><?php endif; ?><div class="news-body"><span class="news-meta"><?= e(ucfirst($item['category'] ?? 'News')) ?> • <?= e(format_date($item['published_at'] ?? $item['created_at'] ?? null, 'd M Y')) ?></span><h3><?= e($item['title']) ?></h3><p><?= e(excerpt($item['content'] ?? '', 180)) ?></p></div></article><?php endforeach; ?></div>
            <?php else: ?>
            <div class="empty-state">No news or notices have been published yet.</div>
            <?php endif; ?>

        <?php elseif ($routeKey === '/gallery'): ?>
            <?php if ($galleryImages): ?>
            <div class="gallery-filters" data-reveal>
                <button type="button" class="gallery-filter active" data-gallery-filter="all">All</button>
                <?php foreach ($albums as $album): ?><button type="button" class="gallery-filter" data-gallery-filter="<?= e($album['slug']) ?>"><?= e($album['title']) ?></button><?php endforeach; ?>
            </div>
            <div class="gallery-grid" data-reveal-group data-gallery-grid>
                <?php foreach ($galleryImages as $image): ?><figure class="gallery-item" data-gallery-item="<?= e($image['album_slug']) ?>"><img src="<?= e(upload_url($image['image'])) ?>" alt="<?= e($image['caption'] ?: $image['album_title']) ?>" loading="lazy"><figcaption><?= e($image['caption'] ?: $image['album_title']) ?></figcaption></figure><?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">No gallery photos have been published yet.</div>
            <?php endif; ?>

        <?php elseif ($routeKey === '/downloads'): ?>
            <?php if ($downloads): ?>
            <div class="download-grid"><?php foreach ($downloads as $category => $items): ?><section class="download-group"><div class="section-row inner-section-row"><div><span class="eyebrow dark"><i></i>Resources</span><h2><?= e($category) ?></h2></div></div><?php foreach ($items as $item): ?><a class="download-item" href="<?= url('/downloads/' . $item['id']) ?>"><span class="download-icon">↓</span><div><strong><?= e($item['title']) ?></strong><small><?= e($item['description'] ?? '') ?><?= !empty($item['file_size']) ? ' • ' . e(\App\Models\Download::humanSize((int)$item['file_size'])) : '' ?></small></div><b>↗</b></a><?php endforeach; ?></section><?php endforeach; ?></div>
            <?php else: ?>
            <div class="empty-state">No downloads have been published yet.</div>
            <?php endif; ?>

        <?php elseif ($routeKey === '/admissions'): ?>
            <div class="admission-page-grid" data-reveal><div><span class="eyebrow dark"><i></i>Admissions 2026–27</span><h2>Give your child a place to <em>grow.</em></h2><div class="rich-content"><?= $body ?: '<p>Applications are welcomed across our published academic pathways. Our admissions team will guide families through documentation, assessment and the next steps.</p>' ?></div><div class="admission-steps"><div><b>01</b><span>Submit application</span></div><div><b>02</b><span>Assessment & meeting</span></div><div><b>03</b><span>Offer & enrolment</span></div></div></div><div class="admission-panel"><span class="eyebrow light"><i></i>Start a conversation</span><h3>Ready to apply?</h3><p>Fill in the application form below, or contact the office directly for current class availability and required documents.</p><a class="btn btn-white" href="#apply">Apply Online ↓</a></div></div>

            <div class="admission-form-section" id="apply" data-reveal>
                <div class="section-row inner-section-row"><div><span class="eyebrow dark"><i></i>Apply now</span><h2>Start your <em>application.</em></h2></div><p>Complete the three steps below — it takes about five minutes.</p></div>

                <div class="admission-form-card">
                    <div class="af-steps">
                        <div class="af-step active" data-step-indicator="1"><span class="af-step-circle">1</span><span class="af-step-label">Personal Info</span></div>
                        <div class="af-step-line"></div>
                        <div class="af-step" data-step-indicator="2"><span class="af-step-circle">2</span><span class="af-step-label">Academic Details</span></div>
                        <div class="af-step-line"></div>
                        <div class="af-step" data-step-indicator="3"><span class="af-step-circle">3</span><span class="af-step-label">Review &amp; Submit</span></div>
                    </div>

                    <form method="post" action="<?= url('/admissions/apply') ?>" class="admission-form" data-admission-form novalidate>
                        <?= App\Core\Csrf::field() ?>

                        <div class="af-panel" data-step="1">
                            <h3 class="af-panel-title">Personal Information</h3>
                            <div class="af-grid">
                                <div class="af-field"><label>Student's Full Name *</label><input type="text" name="student_name" required minlength="3" value="<?= old('student_name') ?>"></div>
                                <div class="af-field"><label>Father's Name *</label><input type="text" name="father_name" required minlength="3" value="<?= old('father_name') ?>"></div>
                                <div class="af-field"><label>B-Form / CNIC *</label><input type="text" name="bform" required placeholder="42101-1234567-1" value="<?= old('bform') ?>"></div>
                                <div class="af-field"><label>Date of Birth *</label><input type="date" name="dob" required value="<?= old('dob') ?>"></div>
                                <div class="af-field af-radio-field"><label>Gender *</label><div class="af-radio-row"><label><input type="radio" name="gender" value="male"<?= old_raw('gender') === 'male' ? ' checked' : '' ?> required> Male</label><label><input type="radio" name="gender" value="female"<?= old_raw('gender') === 'female' ? ' checked' : '' ?>> Female</label></div></div>
                                <div class="af-field"><label>Guardian Phone *</label><input type="tel" name="guardian_phone" required placeholder="0300 1234567" value="<?= old('guardian_phone') ?>"></div>
                                <div class="af-field af-wide"><label>Home Address *</label><input type="text" name="address" required minlength="10" value="<?= old('address') ?>"></div>
                            </div>
                            <div class="af-actions"><span></span><button type="button" class="btn btn-primary" data-af-next>Next <span>→</span></button></div>
                        </div>

                        <div class="af-panel" data-step="2" hidden>
                            <h3 class="af-panel-title">Academic Details</h3>
                            <div class="af-grid">
                                <div class="af-field"><label>Class Applying For *</label>
                                    <select name="class_applied" required>
                                        <option value="">Select a class</option>
                                        <?php foreach (\App\Models\Admission::CLASSES as $c): ?><option value="<?= e($c) ?>"<?= old_raw('class_applied') === $c ? ' selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="af-field"><label>Previous School (if any)</label><input type="text" name="prev_school" value="<?= old('prev_school') ?>"></div>
                                <div class="af-field af-wide"><label>Anything else we should know?</label><textarea name="notes" rows="3"><?= old('notes') ?></textarea></div>
                            </div>
                            <div class="af-actions"><button type="button" class="btn btn-ghost" data-af-back>← Back</button><button type="button" class="btn btn-primary" data-af-next>Next <span>→</span></button></div>
                        </div>

                        <div class="af-panel" data-step="3" hidden>
                            <h3 class="af-panel-title">Review &amp; Submit</h3>
                            <div class="af-review" data-af-review></div>
                            <p class="af-consent">By submitting, you confirm the information above is accurate to the best of your knowledge.</p>
                            <div class="af-actions"><button type="button" class="btn btn-ghost" data-af-back>← Back</button><button type="submit" class="btn btn-primary">Submit Application <span>→</span></button></div>
                        </div>
                    </form>
                </div>
            </div>

        <?php elseif ($routeKey === '/contact'): ?>
            <div class="contact-page-grid" data-reveal><div><span class="eyebrow dark"><i></i>Visit the campus</span><h2>Let’s start the <em>conversation.</em></h2><p>Our office team can help with admissions, fees, academics, transport and general school information.</p><div class="contact-cards"><div><span>Address</span><strong><?= e(setting('address', 'Hafizabad, Punjab, Pakistan')) ?></strong></div><?php if ($phone = setting('phone')): ?><div><span>Phone</span><a href="tel:<?= e(preg_replace('/[^0-9+]/','',$phone)) ?>"><strong><?= e($phone) ?></strong></a></div><?php endif; ?><?php if ($email = setting('email')): ?><div><span>Email</span><a href="mailto:<?= e($email) ?>"><strong><?= e($email) ?></strong></a></div><?php endif; ?></div></div><div class="contact-form-card"><h3>Send us a message</h3><p>Fill in the form and our office will get back to you shortly.</p><form method="post" action="<?= url('/contact/send') ?>" class="contact-form" novalidate><?= App\Core\Csrf::field() ?><div class="af-grid"><div class="af-field"><label>Your Name *</label><input type="text" name="name" required minlength="2" value="<?= old('name') ?>"></div><div class="af-field"><label>Phone</label><input type="tel" name="phone" placeholder="0300 1234567" value="<?= old('phone') ?>"></div><div class="af-field"><label>Email</label><input type="email" name="email" value="<?= old('email') ?>"></div><div class="af-field"><label>Subject</label><input type="text" name="subject" placeholder="Admissions, Fees, General..." value="<?= old('subject') ?>"></div><div class="af-field af-wide"><label>Message *</label><textarea name="message" rows="4" required minlength="10"><?= old('message') ?></textarea></div></div><div class="contact-form-actions"><button type="submit" class="btn btn-primary">Send Message <span>→</span></button></div></form></div></div>

            <?php if ($mapEmbed = setting('map_embed')): ?>
            <div class="contact-map-card" data-reveal>
                <iframe src="<?= e($mapEmbed) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Pakistan Cambridge School location" allowfullscreen></iframe>
            </div>
            <?php endif; ?>

        <?php elseif ($routeKey === '/facilities'): ?>
            <?php if ($body): ?><div class="rich-content intro-copy" data-reveal><?= $body ?></div><?php endif; ?>
            <?php if ($facilities): ?>
            <div class="value-grid inner-value-grid" data-reveal-group>
                <?php foreach ($facilities as $i => $f): ?><article class="value-card"><span class="value-index">0<?= $i + 1 ?></span><div class="value-icon"><?= icon($f['icon'] ?? 'shield', 22) ?></div><h3><?= e($f['title']) ?></h3><p><?= e($f['description']) ?></p></article><?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="fees-empty">
                <div class="fees-empty-icon"><?= icon('shield', 26) ?></div>
                <h4>Facility details coming soon</h4>
                <p>This section is being updated. Contact the office for information about campus facilities.</p>
                <a class="btn btn-ghost" href="<?= url('/contact') ?>">Contact Us →</a>
            </div>
            <?php endif; ?>

        <?php elseif ($routeKey === '/search'): ?>
            <div class="section-row"><div><span class="eyebrow dark"><i></i>Site search</span><h2>Find what you <em>need.</em></h2></div><p>Search published school pages and recent news.</p></div><form class="site-search-form" method="get" action="<?= url('/search') ?>"><input type="search" name="q" value="<?= e($searchTerm ?? '') ?>" placeholder="Search Pakistan Cambridge School..."><button class="btn btn-primary" type="submit">Search</button></form><?php if (($searchTerm ?? '') !== ''): ?><div class="search-results"><h3><?= count($searchResults) ?> result(s) for “<?= e($searchTerm) ?>”</h3><?php foreach ($searchResults as $result): ?><a class="search-result" href="<?= e($result['href']) ?>"><span><?= e($result['type']) ?></span><h4><?= e($result['title']) ?></h4><p><?= e($result['excerpt']) ?></p></a><?php endforeach; ?><?php if (!$searchResults): ?><div class="empty-state">No matching content found.</div><?php endif; ?></div><?php endif; ?>

        <?php else: ?>
            <?php if ($body): ?><div class="rich-content prose-page"><?= $body ?></div><?php else: ?><div class="empty-state">This page is ready for content from the Admin Dashboard.</div><?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<section class="inner-cta"><div class="container"><div><span class="eyebrow light"><i></i>Pakistan Cambridge School</span><h2>Learning today. <em>Leading tomorrow.</em></h2></div><a class="btn btn-white" href="<?= url('/admissions') ?>">Start an application →</a></div></section>
<?php clear_old(); ?>
