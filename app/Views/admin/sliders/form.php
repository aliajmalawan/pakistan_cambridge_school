<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" enctype="multipart/form-data"
      action="<?= url($slider ? '/admin/sliders/update/' . $slider['id'] : '/admin/sliders/store') ?>"
      class="max-w-3xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><?php $f(['name' => 'eyebrow', 'label' => 'Eyebrow (small line above the title)', 'value' => old_raw('eyebrow', $slider['eyebrow'] ?? ''), 'placeholder' => 'Admissions Open — Session 2026–27']); ?></div>
    <div class="sm:col-span-2"><?php $f(['name' => 'title', 'label' => 'Title', 'required' => true, 'value' => old_raw('title', $slider['title'] ?? '')]); ?></div>
    <div class="sm:col-span-2"><?php $f(['name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2, 'value' => old_raw('subtitle', $slider['subtitle'] ?? '')]); ?></div>
    <?php $f(['name' => 'cta_text', 'label' => 'Button Text', 'value' => old_raw('cta_text', $slider['cta_text'] ?? ''), 'placeholder' => 'Apply Now']); ?>
    <?php $f(['name' => 'cta_link', 'label' => 'Button Link', 'value' => old_raw('cta_link', $slider['cta_link'] ?? ''), 'placeholder' => '/admissions', 'help' => 'Site path (/admissions) or full URL']); ?>
    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($slider['sort_order'] ?? '0'))]); ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select', 'value' => old_raw('status', $slider['status'] ?? 'published'), 'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>

    <!-- Image -->
    <div class="sm:col-span-2 rounded-lg border border-ink-100 bg-sunken/50 p-5">
      <h2 class="font-semibold mb-1">Slide Image <span class="font-normal text-ink-500 text-sm">(optional)</span></h2>
      <p class="inline-flex items-center gap-2 h-8 px-3 rounded-full bg-navy/5 text-navy text-sm font-bold mb-3">
        Recommended size: 1600 × 1200&nbsp;px <span class="font-normal text-ink-500">(4:3 ratio)</span>
      </p>
      <p class="text-sm text-ink-500 mb-4">
        Shown beside the headline on the homepage, in a rounded, slightly tilted <strong class="text-ink-700">4:3 frame</strong>
        about 400–460&nbsp;px wide on screen — 1600 × 1200&nbsp;px gives a sharp image at that size on every
        display, including retina. A wider or taller photo still works — it is centred and the edges are
        trimmed to fit, so keep the important part in the middle.
        Without an image the slide shows the school crest instead.
      </p>

      <div class="grid sm:grid-cols-[220px_1fr] gap-5 items-start">
        <!-- Live preview in the real frame shape (30px rounded corners, 2° tilt — matches .hero-card in site.css) -->
        <div>
          <div id="slide-preview-frame"
               class="relative w-full overflow-hidden rounded-[30px] rotate-2 border border-ink-200 bg-navy shadow-kds-sm"
               style="aspect-ratio:4/3">
            <?php if (!empty($slider['image'])): ?>
            <img id="slide-preview" src="<?= upload_url($slider['image']) ?>" alt="Current slide image"
                 class="absolute inset-0 w-full h-full object-cover">
            <?php else: ?>
            <img id="slide-preview" src="" alt="" hidden class="absolute inset-0 w-full h-full object-cover">
            <div id="slide-preview-empty" class="absolute inset-0 flex items-center justify-center text-center px-4">
              <span class="text-[11px] text-white/60 leading-snug">No image —<br>the crest is shown</span>
            </div>
            <?php endif; ?>
          </div>
          <p class="text-[11px] text-ink-500 mt-2 text-center">Exactly how it appears on the homepage</p>
        </div>

        <div>
          <?php $f([
              'name' => 'image',
              'label' => 'Upload a new image',
              'type'  => 'file',
              'help'  => 'JPG, PNG or WEBP up to 4 MB. It is resized and compressed automatically.',
          ]); ?>
          <p id="slide-file-note" class="text-xs text-success font-medium mt-2 hidden"></p>
          <?php if (!empty($slider['image'])): ?>
          <p class="text-xs text-ink-500 mt-3">
            Current file:
            <a href="<?= upload_url($slider['image']) ?>" target="_blank" rel="noopener" class="text-navy font-medium hover:underline">
              <?= e(basename($slider['image'])) ?>
            </a>
            <?php
            $full = UPLOAD_PATH . '/' . $slider['image'];
            if (is_file($full)) {
                $info = @getimagesize($full);
                if ($info) {
                    $ratio = $info[1] > 0 ? round($info[0] / $info[1], 2) : 0;
                    echo '<br><span class="tabular">' . (int) $info[0] . ' × ' . (int) $info[1] . ' px</span>';
                    echo ' · ' . e(App\Models\Download::humanSize(filesize($full)));
                    if ($ratio > 1.5) {
                        echo '<br><span class="text-warning font-medium">This photo is much wider than 4:3, so the left and right edges are trimmed on the homepage.</span>';
                    }
                }
            }
            ?>
          </p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $slider ? 'Save Changes' : 'Create Slider' ?></button>
    <a href="<?= url('/admin/sliders') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>

<script>
(function () {
  "use strict";
  var input = document.getElementById('f_image');
  var preview = document.getElementById('slide-preview');
  var empty = document.getElementById('slide-preview-empty');
  var note = document.getElementById('slide-file-note');
  if (!input || !preview) return;

  input.addEventListener('change', function () {
    var file = input.files && input.files[0];
    if (!file) return;

    preview.src = URL.createObjectURL(file);
    preview.hidden = false;
    if (empty) empty.hidden = true;

    // Report the real dimensions so the crop is never a surprise
    var probe = new Image();
    probe.onload = function () {
      var ratio = probe.height ? probe.width / probe.height : 0;
      var text = file.name + ' — ' + probe.width + ' × ' + probe.height + ' px';
      if (ratio > 1.5) {
        text += '. Much wider than 4:3, so the sides will be trimmed — check the preview.';
        note.className = 'text-xs text-warning font-medium mt-2';
      } else {
        text += '. Press Save Changes to apply it.';
        note.className = 'text-xs text-success font-medium mt-2';
      }
      note.textContent = text;
      note.classList.remove('hidden');
    };
    probe.src = preview.src;
  });
})();
</script>
<?php clear_old(); ?>
