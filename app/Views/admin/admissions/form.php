<?php
$f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a);
$val = fn(string $k) => old_raw($k, (string) ($application[$k] ?? ''));
?>

<a href="<?= url('/admin/admissions/show/' . $application['id']) ?>"
   class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy hover:underline">&larr; Back to application</a>

<form method="post" action="<?= url('/admin/admissions/update/' . $application['id']) ?>"
      class="mt-4 max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm overflow-hidden">
  <?= App\Core\Csrf::field() ?>

  <div class="px-6 py-5 border-b border-ink-100 bg-sunken/40">
    <h2 class="font-heading font-bold text-lg text-navy">Correct the application</h2>
    <p class="text-sm text-ink-500 mt-1">
      <span class="tabular-nums font-semibold text-ink-700"><?= e($application['app_no']) ?></span>
      &middot; submitted <?= format_date($application['created_at'], 'j M Y, g:i A') ?>.
      Use this to fix what the office can verify against the documents &mdash; a misspelt name,
      a wrong digit in a phone number. The decision is set back on the application itself.
    </p>
  </div>

  <!-- 1. Student -->
  <section class="px-6 py-5 border-b border-ink-100">
    <h3 class="flex items-center gap-2.5 font-heading font-bold text-navy mb-4">
      <span class="accent-blue inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-lg text-[11px] font-bold text-white"
            style="background:var(--accent)">1</span>
      Student details
    </h3>
    <div class="grid sm:grid-cols-2 gap-5">
      <?php $f(['name' => 'student_name', 'label' => "Student's full name", 'required' => true, 'value' => $val('student_name')]); ?>
      <?php $f(['name' => 'father_name', 'label' => "Father's name", 'required' => true, 'value' => $val('father_name')]); ?>
      <?php $f([
          'name' => 'bform', 'label' => 'B-Form / CNIC', 'required' => true, 'value' => $val('bform'),
          'placeholder' => '00000-0000000-0',
          'help' => '13 digits. Dashes are added automatically as you type.',
      ]); ?>
      <?php $f(['name' => 'dob', 'label' => 'Date of birth', 'type' => 'date', 'required' => true, 'value' => $val('dob')]); ?>
      <?php $f([
          'name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'required' => true,
          'value' => $val('gender'),
          'options' => ['male' => 'Male', 'female' => 'Female'],
      ]); ?>
    </div>
  </section>

  <!-- 2. Applying for -->
  <section class="px-6 py-5 border-b border-ink-100">
    <h3 class="flex items-center gap-2.5 font-heading font-bold text-navy mb-4">
      <span class="accent-teal inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-lg text-[11px] font-bold text-white"
            style="background:var(--accent)">2</span>
      Applying for
    </h3>
    <div class="grid sm:grid-cols-2 gap-5">
      <?php $f([
          'name' => 'class_applied', 'label' => 'Class', 'type' => 'select', 'required' => true,
          'value' => $val('class_applied'),
          'options' => array_combine($classes, $classes),
      ]); ?>
      <?php $f(['name' => 'prev_school', 'label' => 'Previous school', 'value' => $val('prev_school')]); ?>
    </div>
  </section>

  <!-- 3. Guardian -->
  <section class="px-6 py-5 border-b border-ink-100">
    <h3 class="flex items-center gap-2.5 font-heading font-bold text-navy mb-4">
      <span class="accent-violet inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-lg text-[11px] font-bold text-white"
            style="background:var(--accent)">3</span>
      Guardian &amp; home
    </h3>
    <div class="grid sm:grid-cols-2 gap-5">
      <?php $f([
          'name' => 'guardian_phone', 'label' => 'Guardian phone', 'required' => true,
          'value' => $val('guardian_phone'), 'placeholder' => '0300 1234567',
      ]); ?>
      <div class="sm:col-span-2">
        <?php $f(['name' => 'address', 'label' => 'Home address', 'required' => true, 'value' => $val('address')]); ?>
      </div>
    </div>
  </section>

  <!-- 4. Notes -->
  <section class="px-6 py-5">
    <h3 class="flex items-center gap-2.5 font-heading font-bold text-navy mb-4">
      <span class="accent-amber inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-lg text-[11px] font-bold text-white"
            style="background:var(--accent)">4</span>
      Anything else
    </h3>
    <?php $f([
        'name' => 'notes', 'label' => 'Notes from the applicant', 'type' => 'textarea', 'rows' => 3,
        'value' => $val('notes'),
        'help' => 'What the guardian wrote. Edit only to correct it, not to add office remarks.',
    ]); ?>
  </section>

  <div class="px-6 py-5 border-t border-ink-100 flex flex-wrap gap-3 bg-sunken/40">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition">Save Changes</button>
    <a href="<?= url('/admin/admissions/show/' . $application['id']) ?>"
       class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
