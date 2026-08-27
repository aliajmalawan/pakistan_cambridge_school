<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post"
      action="<?= url($program ? '/admin/programs/update/' . $program['id'] : '/admin/programs/store') ?>"
      class="max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php $f(['name' => 'name', 'label' => 'Program Name', 'required' => true, 'value' => old_raw('name', $program['name'] ?? ''), 'placeholder' => 'Matriculation — Science']); ?>
    <?php $f(['name' => 'level', 'label' => 'Level / Classes', 'required' => true, 'value' => old_raw('level', $program['level'] ?? ''), 'placeholder' => 'Grades IX – X']); ?>
    <div class="sm:col-span-2"><?php $f(['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4, 'required' => true, 'value' => old_raw('description', $program['description'] ?? '')]); ?></div>
    <div class="sm:col-span-2"><?php $f(['name' => 'subjects', 'label' => 'Subjects (comma-separated)', 'value' => old_raw('subjects', $program['subjects'] ?? ''), 'help' => 'Shown as chips on the program card']); ?></div>
    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($program['sort_order'] ?? '0'))]); ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $program['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>
  </div>
  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $program ? 'Save Changes' : 'Create Program' ?></button>
    <a href="<?= url('/admin/programs') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
