<?php
$f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a);
$isSystem = $page !== null && App\Models\Page::isSystem($page);
?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($page ? '/admin/pages/update/' . $page['id'] : '/admin/pages/store') ?>"
      class="max-w-4xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>

  <?php if ($isSystem): ?>
  <div class="mb-6 rounded-lg border border-info/30 bg-info/5 px-5 py-4 text-sm text-ink-700">
    <p class="font-semibold text-navy mb-1">System page — <code class="bg-white px-1.5 py-0.5 rounded text-xs"><?= e($page['route']) ?></code></p>
    <p>The body of this page is generated from live data, so its URL and layout are fixed. What you set here is the heading, the introduction shown beneath it, and the text search engines display.</p>
  </div>
  <?php endif; ?>

  <div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'title', 'label' => 'Page Heading', 'required' => true,
          'value' => old_raw('title', $page['title'] ?? ''),
          'help'  => 'Shown as the large heading at the top of the page, and as the browser tab title.',
      ]); ?>
    </div>

    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'lede', 'label' => 'Introduction line', 'type' => 'textarea', 'rows' => 2,
          'value' => old_raw('lede', $page['lede'] ?? ''),
          'help'  => 'One sentence under the heading. Leave blank to show none.',
      ]); ?>
    </div>

    <?php if (!$isSystem): ?>
    <?php $f([
        'name' => 'slug', 'label' => 'URL Slug', 'value' => old_raw('slug', $page['slug'] ?? ''),
        'help'  => 'Leave blank to build it from the heading. The page lives at /page/<slug>',
    ]); ?>
    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($page['sort_order'] ?? '0'))]); ?>
    <?php endif; ?>

    <div class="sm:col-span-2">
      <label for="f_content" class="text-sm font-medium text-ink-700 block mb-1.5">
        <?= $isSystem ? 'Introduction block (HTML, optional)' : 'Body Content (HTML)' ?>
        <?= $isSystem ? '' : '<span class="text-danger">*</span>' ?>
      </label>
      <textarea id="f_content" name="content" rows="<?= $isSystem ? 6 : 16 ?>" <?= $isSystem ? '' : 'required' ?>
                class="w-full rounded border border-ink-300 bg-sunken px-3 py-2.5 font-mono text-[13px] resize-y focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15"><?= old('content', $page['content'] ?? '') ?></textarea>
      <span class="text-xs text-ink-500 mt-1 block">
        <?php if ($isSystem): ?>
        Appears between the heading and the generated content. Leave empty to show nothing.
        <?php else: ?>
        Use headings (&lt;h2&gt;, &lt;h3&gt;), paragraphs (&lt;p&gt;) and lists (&lt;ul&gt;&lt;li&gt;) — the site styles them automatically.
        <?php endif; ?>
      </span>
    </div>

    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'meta_description', 'label' => 'Search engine description',
          'value' => old_raw('meta_description', $page['meta_description'] ?? ''),
          'help'  => 'One sentence shown under the title in Google results. Around 150 characters.',
      ]); ?>
    </div>

    <?php $f([
        'name' => 'status', 'label' => 'Status', 'type' => 'select',
        'value' => old_raw('status', $page['status'] ?? 'published'),
        'options' => $isSystem
            ? ['published' => 'Published']
            : ['published' => 'Published', 'draft' => 'Draft'],
        'help' => $isSystem ? 'A system page cannot be hidden — remove it from the menu instead.' : null,
    ]); ?>

    <?php if (!$isSystem): ?>
    <div class="sm:col-span-2 rounded-lg border border-ink-100 bg-sunken/50 px-4 py-3 text-sm text-ink-500">
      <span class="font-medium text-ink-700">Navigation:</span>
      menu placement is managed separately.
      <a href="<?= url('/admin/menu') ?>" class="text-navy font-semibold hover:underline">Open Menu Builder</a>
      and add a <em>CMS page</em> item pointing at this page.
    </div>
    <?php endif; ?>

    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'image', 'label' => 'Header Image (optional)', 'type' => 'file',
          'help' => 'Recommended 1920 × 600 px — a wide, short banner. JPG, PNG or WebP, up to 4 MB.',
      ]); ?>

      <p class="mt-2 text-xs text-ink-500">
        Fills the navy band behind the page heading. A larger photo is fine — it is resized to
        1920&nbsp;px wide automatically. The band is short and full-width, so the picture is cropped
        from the centre: keep the important part in the middle, not near the top or bottom.
        The heading sits over the left half, which is darkened for readability — avoid photos with
        anything essential on that side. Anything under 1400&nbsp;px wide will look soft on a large screen.
      </p>

      <?php if (!empty($page['image'])): ?>
      <?php
        // Show what is actually stored, so an admin can see at a glance whether
        // the current file meets the guidance above.
        $imgPath = UPLOAD_PATH . '/' . ltrim($page['image'], '/');
        $dims = is_file($imgPath) ? @getimagesize($imgPath) : false;
      ?>
      <div class="mt-3 flex items-center gap-4">
        <!-- Shown at the band's own proportions, so this preview is what the page will look like -->
        <img src="<?= upload_url($page['image']) ?>" alt="Current header image"
             class="h-24 rounded-lg object-cover" style="aspect-ratio:16/5">
        <?php if ($dims): ?>
        <span class="text-xs text-ink-500">
          Current image: <span class="tabular-nums font-medium text-ink-700"><?= (int) $dims[0] ?> × <?= (int) $dims[1] ?> px</span>
          <?php if ($dims[0] < 1400): ?>
          <span class="block mt-1 text-warning font-medium">Narrower than 1400 px — replace it if it looks soft across the band.</span>
          <?php endif; ?>
        </span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $page ? 'Save Changes' : 'Create Page' ?></button>
    <a href="<?= url('/admin/pages') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
    <?php if ($page): ?>
    <a href="<?= url($isSystem ? $page['route'] : '/page/' . $page['slug']) ?>" target="_blank" rel="noopener"
       class="inline-flex items-center h-11 px-5 rounded-lg text-navy font-medium hover:bg-sunken transition ml-auto">View page ↗</a>
    <?php endif; ?>
  </div>
</form>
<?php clear_old(); ?>
