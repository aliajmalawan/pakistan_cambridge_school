<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($item ? '/admin/downloads/update/' . $item['id'] : '/admin/downloads/store') ?>"
      class="max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><?php $f(['name' => 'title', 'label' => 'Title', 'required' => true, 'value' => old_raw('title', $item['title'] ?? ''), 'placeholder' => 'Admission Form 2026-27']); ?></div>
    <div class="sm:col-span-2"><?php $f(['name' => 'description', 'label' => 'Description (optional)', 'type' => 'textarea', 'rows' => 2, 'value' => old_raw('description', $item['description'] ?? '')]); ?></div>
    <?php $f(['name' => 'category', 'label' => 'Category', 'type' => 'select', 'value' => old_raw('category', $item['category'] ?? 'general'), 'options' => \App\Models\Download::CATEGORIES]); ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $item['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>

    <div class="sm:col-span-2">
      <?php $f(['name' => 'file', 'label' => 'Upload a file (optional)', 'type' => 'file', 'accept' => '.pdf,.doc,.docx,.xls,.xlsx', 'help' => 'PDF, DOC, DOCX, XLS or XLSX — up to 8 MB.']); ?>
      <?php if (!empty($item['file_path'])): ?>
      <div class="mt-3 flex flex-wrap items-start gap-4">
        <a href="<?= upload_url($item['file_path']) ?>" target="_blank" rel="noopener" class="inline-flex items-center h-10 px-4 rounded-lg bg-sunken text-sm text-navy font-medium hover:brightness-95 transition">
          Current file (<?= e(strtoupper($item['file_ext'] ?? '')) ?><?= $item['file_size'] ? ', ' . e(\App\Models\Download::humanSize((int) $item['file_size'])) : '' ?>)
        </a>
        <label class="inline-flex items-center gap-2.5 text-sm text-ink-700 cursor-pointer">
          <input type="hidden" name="file_remove" value="0">
          <input type="checkbox" name="file_remove" value="1" class="w-[18px] h-[18px] accent-danger">
          <span>Remove this file</span>
        </label>
      </div>
      <?php endif; ?>
    </div>

    <div class="sm:col-span-2">
      <?php $f(['name' => 'external_url', 'label' => 'Or link to an external file (optional)', 'value' => old_raw('external_url', $item['external_url'] ?? ''), 'placeholder' => 'https://...', 'help' => 'Used only when no file is uploaded above — e.g. a Google Drive or another site link.']); ?>
    </div>
  </div>
  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $item ? 'Save Changes' : 'Add Download' ?></button>
    <a href="<?= url('/admin/downloads') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
