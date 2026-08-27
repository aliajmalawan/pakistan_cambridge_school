<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>

<div class="grid lg:grid-cols-2 gap-6 items-start">
  <!-- Album details -->
  <form method="post" enctype="multipart/form-data"
        action="<?= url($album ? '/admin/gallery/update/' . $album['id'] : '/admin/gallery/store') ?>"
        class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6">
    <?= App\Core\Csrf::field() ?>
    <h2 class="font-semibold mb-5"><?= $album ? 'Album Details' : 'New Album' ?></h2>
    <div class="grid gap-5">
      <?php $f(['name' => 'title', 'label' => 'Album Title', 'required' => true, 'value' => old_raw('title', $album['title'] ?? '')]); ?>
      <?php $f(['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 2, 'value' => old_raw('description', $album['description'] ?? '')]); ?>
      <div class="grid grid-cols-2 gap-4">
        <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($album['sort_order'] ?? '0'))]); ?>
        <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $album['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>
      </div>
      <div>
        <?php $f(['name' => 'cover', 'label' => 'Cover Image', 'type' => 'file']); ?>
        <?php if (!empty($album['cover'])): ?>
        <img src="<?= upload_url($album['cover']) ?>" alt="Current cover" class="mt-3 h-24 rounded-lg object-cover">
        <?php endif; ?>
      </div>
    </div>
    <div class="mt-6 flex gap-3">
      <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $album ? 'Save Album' : 'Create Album' ?></button>
      <a href="<?= url('/admin/gallery') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Back</a>
    </div>
  </form>

  <!-- Photo upload (existing albums only) -->
  <?php if ($album): ?>
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6">
    <h2 class="font-semibold mb-5">Upload Photos</h2>
    <form method="post" enctype="multipart/form-data" action="<?= url('/admin/gallery/' . $album['id'] . '/images') ?>">
      <?= App\Core\Csrf::field() ?>
      <div class="grid gap-5">
        <?php $f(['name' => 'images[]', 'label' => 'Photos (select multiple)', 'type' => 'file', 'multiple' => true]); ?>
        <?php $f(['name' => 'caption', 'label' => 'Caption (applied to this batch, optional)', 'value' => '']); ?>
      </div>
      <button type="submit" class="mt-5 inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition">Upload to Album</button>
    </form>
  </div>
  <?php endif; ?>
</div>

<!-- Photos grid -->
<?php if ($album && $images): ?>
<div class="mt-6 bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6">
  <h2 class="font-semibold mb-4"><?= count($images) ?> Photos in "<?= e($album['title']) ?>"</h2>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    <?php foreach ($images as $img): ?>
    <div class="group relative rounded-lg overflow-hidden aspect-square bg-sunken">
      <img src="<?= upload_url($img['image']) ?>" alt="<?= e($img['caption']) ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-navy-950/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
        <form method="post" action="<?= url('/admin/gallery/images/delete/' . $img['id']) ?>"
              onsubmit="return confirm('Remove this photo?');">
          <?= App\Core\Csrf::field() ?>
          <button type="submit" class="h-9 px-4 rounded-lg bg-danger text-white text-xs font-semibold">Delete</button>
        </form>
      </div>
      <?php if ($img['caption']): ?>
      <span class="absolute bottom-0 inset-x-0 bg-navy-950/70 text-white text-[10px] px-2 py-1 truncate"><?= e($img['caption']) ?></span>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php elseif ($album): ?>
<div class="mt-6 text-center py-12 border-[1.5px] border-dashed border-ink-300 rounded-xl bg-white">
  <p class="text-sm text-ink-500">No photos in this album yet — upload the first batch above.</p>
</div>
<?php endif; ?>
<?php clear_old(); ?>
