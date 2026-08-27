<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($member ? '/admin/faculty/update/' . $member['id'] : '/admin/faculty/store') ?>"
      class="max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php $f(['name' => 'name', 'label' => 'Full Name', 'required' => true, 'value' => old_raw('name', $member['name'] ?? '')]); ?>
    <?php $f(['name' => 'designation', 'label' => 'Designation', 'required' => true, 'value' => old_raw('designation', $member['designation'] ?? ''), 'placeholder' => 'Senior Lecturer']); ?>
    <?php $f(['name' => 'department', 'label' => 'Department / Subject', 'value' => old_raw('department', $member['department'] ?? ''), 'placeholder' => 'Physics']); ?>
    <?php $f(['name' => 'qualification', 'label' => 'Qualification', 'value' => old_raw('qualification', $member['qualification'] ?? ''), 'placeholder' => 'M.Sc Physics — University of Peshawar']); ?>
    <div class="sm:col-span-2"><?php $f(['name' => 'bio', 'label' => 'Short Bio (optional)', 'type' => 'textarea', 'rows' => 3, 'value' => old_raw('bio', $member['bio'] ?? '')]); ?></div>
    <?php if ($member): ?><?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) $member['sort_order']), 'help' => 'Lower numbers show first on the Faculty page.']); ?><?php endif; ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $member['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>
    <div class="sm:col-span-2">
      <?php App\Core\View::partial('admin/partials/image_field', [
          'name'    => 'photo',
          'label'   => 'Photo (optional)',
          'current' => $member['photo'] ?? null,
          'shape'   => 'square',
          'help'    => 'Square photo, 800 × 800 px. Larger is fine — it is resized to 800 px automatically. Head and shoulders, centred; the card crops to a square.',
      ]); ?>
    </div>
  </div>
  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $member ? 'Save Changes' : 'Add Member' ?></button>
    <a href="<?= url('/admin/faculty') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
