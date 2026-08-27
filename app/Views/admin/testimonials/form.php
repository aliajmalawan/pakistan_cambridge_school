<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($testimonial ? '/admin/testimonials/update/' . $testimonial['id'] : '/admin/testimonials/store') ?>"
      class="max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php $f(['name' => 'name', 'label' => 'Name', 'required' => true, 'value' => old_raw('name', $testimonial['name'] ?? '')]); ?>
    <?php $f(['name' => 'role', 'label' => 'Role', 'value' => old_raw('role', $testimonial['role'] ?? ''), 'placeholder' => 'Parent — Grade IV / Alumna — Class of 2023']); ?>
    <div class="sm:col-span-2"><?php $f(['name' => 'content', 'label' => 'Quote', 'type' => 'textarea', 'rows' => 4, 'required' => true, 'value' => old_raw('content', $testimonial['content'] ?? '')]); ?></div>
    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($testimonial['sort_order'] ?? '0'))]); ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $testimonial['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>
    <div class="sm:col-span-2">
      <?php $f(['name' => 'photo', 'label' => 'Photo (optional)', 'type' => 'file']); ?>
      <?php if (!empty($testimonial['photo'])): ?>
      <img src="<?= upload_url($testimonial['photo']) ?>" alt="Current photo" class="mt-3 h-20 w-20 rounded-full object-cover">
      <?php endif; ?>
    </div>
  </div>
  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $testimonial ? 'Save Changes' : 'Add Testimonial' ?></button>
    <a href="<?= url('/admin/testimonials') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
