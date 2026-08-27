<form method="post" action="<?= url('/admin/settings') ?>" enctype="multipart/form-data" class="max-w-3xl">
  <?= App\Core\Csrf::field() ?>

  <!-- Institution logo -->
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 mb-5">
    <h2 class="font-semibold">Institution Logo</h2>
    <p class="text-sm text-ink-500 mt-1">Used in the website header and footer, the admin panel, the browser tab icon, and social media previews. Uploading a new file regenerates every size automatically.</p>

    <div class="mt-5 flex flex-col sm:flex-row gap-6">
      <!-- Preview -->
      <div class="shrink-0">
        <div class="w-28 h-28 rounded-xl border border-ink-100 bg-sunken flex items-center justify-center overflow-hidden">
          <img id="logo-preview" src="<?= e(logo_url(192)) ?>" alt="Current logo" width="112" height="112" class="w-full h-full object-contain p-2">
        </div>
        <p class="text-[11px] text-ink-500 mt-2 text-center">
          <?= $hasLogo ? 'Uploaded logo' : 'Default crest' ?>
        </p>
      </div>

      <!-- Upload -->
      <div class="grow min-w-0">
        <label for="f_logo" class="text-sm font-medium text-ink-700 block mb-1.5">Upload a new logo</label>
        <input id="f_logo" type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/gif"
               class="w-full text-sm text-ink-500 file:mr-3 file:h-10 file:px-4 file:rounded-lg file:border-0 file:bg-navy file:text-paper file:font-semibold file:text-sm file:cursor-pointer hover:file:brightness-110">
        <p class="text-xs text-ink-500 mt-2 leading-relaxed">
          PNG with a transparent background works best. Use a square image of at least 512×512 pixels — anything smaller than 128×128 is rejected. Maximum 4 MB. Non-square images are centred on a transparent square, never stretched.
        </p>
        <p id="logo-filename" class="text-xs text-success font-medium mt-2 hidden"></p>

        <?php if ($hasLogo): ?>
        <div class="mt-4 pt-4 border-t border-ink-100 flex items-center gap-3 flex-wrap">
          <span class="text-xs text-ink-500">Want the original crest back?</span>
          <button type="button" id="remove-logo-btn"
                  class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-danger hover:bg-danger/10 transition">
            Remove uploaded logo
          </button>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Setting groups -->
  <?php foreach ($groups as $groupName => $fields): ?>
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 mb-5">
    <h2 class="font-semibold mb-5"><?= e($groupName) ?></h2>
    <div class="grid sm:grid-cols-2 gap-5">
      <?php foreach ($fields as $key => [$label, $type]): ?>
      <div class="<?= $type === 'textarea' ? 'sm:col-span-2' : '' ?> flex flex-col gap-1.5">
        <label for="s_<?= e($key) ?>" class="text-sm font-medium text-ink-700"><?= e($label) ?></label>
        <?php if ($type === 'textarea'): ?>
        <textarea id="s_<?= e($key) ?>" name="<?= e($key) ?>" rows="3"
                  class="w-full rounded border border-ink-300 bg-sunken px-3 py-2.5 resize-y focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15"><?= e($values[$key] ?? '') ?></textarea>
        <?php else: ?>
        <input id="s_<?= e($key) ?>" name="<?= e($key) ?>" value="<?= e($values[$key] ?? '') ?>"
               class="h-11 w-full rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>

  <button type="submit" class="inline-flex items-center h-12 px-8 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition">Save All Settings</button>
</form>

<?php if ($hasLogo): ?>
<!-- Separate form so removing the logo never submits half-edited settings -->
<form id="remove-logo-form" method="post" action="<?= url('/admin/settings/logo/remove') ?>" class="hidden">
  <?= App\Core\Csrf::field() ?>
</form>
<?php endif; ?>

<script>
(function () {
  "use strict";

  // Live preview of the chosen file before saving
  var input = document.getElementById('f_logo');
  var preview = document.getElementById('logo-preview');
  var filename = document.getElementById('logo-filename');
  if (input && preview) {
    input.addEventListener('change', function () {
      var file = input.files && input.files[0];
      if (!file) return;
      preview.src = URL.createObjectURL(file);
      if (filename) {
        filename.textContent = 'Previewing "' + file.name + '" — press Save All Settings to apply it.';
        filename.classList.remove('hidden');
      }
    });
  }

  // Remove logo, with confirmation
  var removeBtn = document.getElementById('remove-logo-btn');
  var removeForm = document.getElementById('remove-logo-form');
  if (removeBtn && removeForm) {
    removeBtn.addEventListener('click', function () {
      if (confirm('Remove the uploaded logo and go back to the default PCS crest?')) {
        removeForm.submit();
      }
    });
  }
})();
</script>
