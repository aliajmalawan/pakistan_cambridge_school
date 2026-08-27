<?php
$f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a);
$linkType = old_raw('link_type', $item['link_type'] ?? 'route');
?>
<form method="post" action="<?= url($item ? '/admin/menu/update/' . $item['id'] : '/admin/menu/store') ?>"
      class="max-w-2xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>

  <div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'label', 'label' => 'Menu Label', 'required' => true,
          'value' => old_raw('label', $item['label'] ?? ''),
          'placeholder' => 'Admissions',
          'help' => 'The text visitors see in the navigation bar. Keep it short — one or two words.',
      ]); ?>
    </div>

    <!-- Link type -->
    <div class="sm:col-span-2">
      <span class="text-sm font-medium text-ink-700 block mb-2">Where should this link go? <span class="text-danger">*</span></span>
      <div class="grid sm:grid-cols-3 gap-2.5" role="radiogroup" aria-label="Link type">
        <?php foreach (App\Models\MenuItem::LINK_TYPES as $value => $label): ?>
        <label class="flex items-start gap-2.5 px-3 py-2.5 rounded-lg border cursor-pointer transition
                      <?= $linkType === $value ? 'border-navy bg-navy-50' : 'border-ink-100 hover:bg-sunken/60' ?>">
          <input type="radio" name="link_type" value="<?= e($value) ?>" <?= $linkType === $value ? 'checked' : '' ?>
                 class="mt-0.5 w-[18px] h-[18px] accent-navy link-type-radio">
          <span class="text-sm">
            <span class="font-medium block"><?= e($label) ?></span>
            <span class="text-xs text-ink-500">
              <?= $value === 'route' ? 'A section the site already has' : ($value === 'page' ? 'A page you wrote yourself' : 'Any other address') ?>
            </span>
          </span>
        </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Route picker -->
    <div class="sm:col-span-2 link-target" data-for="route" <?= $linkType === 'route' ? '' : 'hidden' ?>>
      <?php $f([
          'name' => 'route', 'label' => 'Built-in page', 'type' => 'select',
          'value' => old_raw('route', $item['route'] ?? '/'),
          'options' => App\Models\MenuItem::ROUTES,
      ]); ?>
    </div>

    <!-- CMS page picker -->
    <div class="sm:col-span-2 link-target" data-for="page" <?= $linkType === 'page' ? '' : 'hidden' ?>>
      <?php
      $pageOptions = ['' => 'Select a page…'];
      foreach ($pages as $p) {
          $pageOptions[$p['id']] = $p['title'] . '  (/page/' . $p['slug'] . ')';
      }
      $f([
          'name' => 'page_id', 'label' => 'CMS page', 'type' => 'select',
          'value' => old_raw('page_id', (string) ($item['page_id'] ?? '')),
          'options' => $pageOptions,
          'help' => count($pages) === 0 ? 'No published pages yet — create one under Pages first.' : 'Only published pages are listed.',
      ]); ?>
    </div>

    <!-- Custom URL -->
    <div class="sm:col-span-2 link-target" data-for="custom" <?= $linkType === 'custom' ? '' : 'hidden' ?>>
      <?php $f([
          'name' => 'custom_url', 'label' => 'Custom URL',
          'value' => old_raw('custom_url', $item['custom_url'] ?? ''),
          'placeholder' => 'https://example.com  or  /some/path',
          'help' => 'A full address starting with https://, or a path on this site starting with /',
      ]); ?>
    </div>

    <!-- Placement -->
    <?php
    $parentOptions = ['0' => 'Top level (shown directly in the bar)'];
    foreach ($parents as $p) {
        $parentOptions[$p['id']] = 'Inside “' . $p['label'] . '” dropdown';
    }
    $f([
        'name' => 'parent_id', 'label' => 'Placement', 'type' => 'select',
        'value' => old_raw('parent_id', (string) ($item['parent_id'] ?? '0')),
        'options' => $parentOptions,
        'help' => 'Choosing a parent turns this into a dropdown entry.',
    ]);
    ?>

    <?php $f([
        'name' => 'status', 'label' => 'Status', 'type' => 'select',
        'value' => old_raw('status', $item['status'] ?? 'published'),
        'options' => ['published' => 'Published — visible to visitors', 'draft' => 'Draft — hidden from the site'],
    ]); ?>

    <div class="sm:col-span-2">
      <?php $f([
          'name' => 'new_tab', 'label' => 'Open this link in a new browser tab', 'type' => 'checkbox',
          'checked' => old_raw('new_tab', (string) ($item['new_tab'] ?? '0')) === '1',
      ]); ?>
    </div>
  </div>

  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $item ? 'Save Changes' : 'Add to Menu' ?></button>
    <a href="<?= url('/admin/menu') ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>

<script>
(function () {
  "use strict";
  var radios = document.querySelectorAll('.link-type-radio');
  var targets = document.querySelectorAll('.link-target');
  if (!radios.length) return;

  var sync = function () {
    var chosen = document.querySelector('.link-type-radio:checked');
    var value = chosen ? chosen.value : 'route';
    targets.forEach(function (node) { node.hidden = node.dataset.for !== value; });
    radios.forEach(function (radio) {
      var card = radio.closest('label');
      var on = radio.checked;
      card.classList.toggle('border-navy', on);
      card.classList.toggle('bg-navy-50', on);
      card.classList.toggle('border-ink-100', !on);
    });
  };
  radios.forEach(function (radio) { radio.addEventListener('change', sync); });
  sync();
})();
</script>
<?php clear_old(); ?>
