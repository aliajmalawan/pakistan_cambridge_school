<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($item ? '/admin/news/update/' . $item['id'] : '/admin/news/store') ?>"
      class="max-w-4xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><?php $f(['name' => 'title', 'label' => 'Title', 'required' => true, 'value' => old_raw('title', $item['title'] ?? '')]); ?></div>
    <?php $f(['name' => 'category', 'label' => 'Category', 'type' => 'select', 'value' => old_raw('category', $item['category'] ?? 'news'), 'options' => App\Models\News::CATEGORIES]); ?>
    <?php $f(['name' => 'published_at', 'label' => 'Publish Date & Time', 'type' => 'datetime-local', 'value' => old_raw('published_at', isset($item['published_at']) ? date('Y-m-d\TH:i', strtotime($item['published_at'])) : date('Y-m-d\TH:i')), 'help' => 'Future dates schedule the post']); ?>
    <div class="sm:col-span-2"><?php $f(['name' => 'excerpt', 'label' => 'Excerpt (summary shown in lists)', 'type' => 'textarea', 'rows' => 2, 'value' => old_raw('excerpt', $item['excerpt'] ?? ''), 'help' => 'Leave blank to auto-generate from the body']); ?></div>
    <div class="sm:col-span-2">
      <label for="f_content" class="text-sm font-medium text-ink-700 block mb-1.5">Body Content (HTML) <span class="text-danger">*</span></label>
      <textarea id="f_content" name="content" rows="14" required
                class="w-full rounded border border-ink-300 bg-sunken px-3 py-2.5 font-mono text-[13px] resize-y focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15"><?= old('content', $item['content'] ?? '') ?></textarea>
    </div>
    <?php $f(['name' => 'slug', 'label' => 'Slug', 'value' => old_raw('slug', $item['slug'] ?? ''), 'help' => 'Leave blank to generate from the title']); ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $item['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>
    <div class="sm:col-span-2">
      <?php $f(['name' => 'image', 'label' => 'Featured Image (optional)', 'type' => 'file', 'help' => 'Shown on cards and the article page. Recommended 1200×675.']); ?>
      <?php if (!empty($item['image'])): ?>
      <img src="<?= upload_url($item['image']) ?>" alt="Current featured image" class="mt-3 h-28 rounded-lg object-cover">
      <?php endif; ?>
    </div>
  </div>
  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $item ? 'Save Changes' : 'Publish Post' ?></button>
    <a href="<?= url('/admin/news') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
