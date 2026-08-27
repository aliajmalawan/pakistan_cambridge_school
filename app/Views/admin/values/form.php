<?php $f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a); ?>
<form method="post" action="<?= url($value ? '/admin/values/update/' . $value['id'] : '/admin/values/store') ?>"
      class="max-w-2xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>

  <div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
      <?php $f(['name' => 'title', 'label' => 'Value', 'required' => true, 'value' => old_raw('title', $value['title'] ?? ''), 'placeholder' => 'Excellence']); ?>
    </div>
    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'description', 'label' => 'What it means', 'type' => 'textarea', 'rows' => 3, 'required' => true,
          'value' => old_raw('description', $value['description'] ?? ''),
          'help'  => 'One or two sentences. Say what the school actually does, not what it aspires to.',
      ]); ?>
    </div>

    <?php $f([
        'name' => 'icon', 'label' => 'Icon', 'type' => 'select',
        'value' => old_raw('icon', $value['icon'] ?? 'shield'),
        'options' => App\Models\CoreValue::ICONS,
    ]); ?>
    <?php $f([
        'name' => 'accent', 'label' => 'Colour', 'type' => 'select',
        'value' => old_raw('accent', $value['accent'] ?? 'accent-blue'),
        'options' => App\Models\CoreValue::ACCENTS,
    ]); ?>

    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'value' => old_raw('sort_order', (string) ($value['sort_order'] ?? '0'))]); ?>
    <?php $f([
        'name' => 'status', 'label' => 'Status', 'type' => 'select',
        'value' => old_raw('status', $value['status'] ?? 'published'),
        'options' => ['published' => 'Published', 'draft' => 'Draft'],
    ]); ?>

    <!-- Live preview of the icon + colour pairing -->
    <div class="sm:col-span-2 rounded-lg border border-ink-100 bg-sunken/50 p-5">
      <p class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-3">Preview</p>
      <div id="value-preview" class="<?= e(old_raw('accent', $value['accent'] ?? 'accent-blue')) ?> bg-white border border-ink-100 rounded-xl p-5 max-w-xs">
        <span id="preview-icon" class="w-12 h-12 rounded-xl mb-3 inline-flex items-center justify-center text-white" style="background:var(--accent)">
          <?= App\Core\Icon::svg(old_raw('icon', $value['icon'] ?? 'shield'), 22) ?>
        </span>
        <div id="preview-title" class="font-heading font-bold text-navy"><?= e(old_raw('title', $value['title'] ?? 'Value title')) ?></div>
        <p id="preview-desc" class="text-xs text-ink-500 mt-1.5 leading-relaxed"><?= e(excerpt(old_raw('description', $value['description'] ?? 'What this value means in practice.'), 110)) ?></p>
      </div>
    </div>
  </div>

  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $value ? 'Save Changes' : 'Add Value' ?></button>
    <a href="<?= url('/admin/values') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>

<script>
(function () {
  "use strict";
  var accents = <?= json_encode(array_keys(App\Models\CoreValue::ACCENTS)) ?>;
  var preview = document.getElementById('value-preview');
  var title = document.getElementById('f_title');
  var desc = document.getElementById('f_description');
  var accent = document.getElementById('f_accent');
  if (!preview) return;

  if (accent) accent.addEventListener('change', function () {
    accents.forEach(function (a) { preview.classList.remove(a); });
    preview.classList.add(accent.value);
  });
  if (title) title.addEventListener('input', function () {
    document.getElementById('preview-title').textContent = title.value || 'Value title';
  });
  if (desc) desc.addEventListener('input', function () {
    var t = desc.value || 'What this value means in practice.';
    document.getElementById('preview-desc').textContent = t.length > 110 ? t.slice(0, 110) + '…' : t;
  });
})();
</script>
<?php clear_old(); ?>
