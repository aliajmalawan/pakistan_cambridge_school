<?php
/**
 * Shared form for registry-driven admin modules (see Admin\SectionController).
 * expects: $base, $kind, $spec, $row
 */
$f = fn(array $a) => App\Core\View::partial('admin/partials/field', $a);
?>
<form method="post"
      action="<?= url($base . '/' . $kind . ($row ? '/update/' . $row['id'] : '/store')) ?>"
      class="max-w-2xl bg-white rounded-xl border border-ink-100 shadow-kds-sm p-6 md:p-8">
  <?= App\Core\Csrf::field() ?>

  <div class="grid sm:grid-cols-2 gap-5">
    <?php foreach ($spec['fields'] as $field): ?>
    <?php
      $name = $field['name'];
      $type = $field['type'] ?? 'text';
      $wide = !empty($field['wide']) || $type === 'textarea';
      $args = [
          'name'     => $name,
          'label'    => $field['label'],
          'type'     => $type,
          'required' => !empty($field['required']),
      ];
      foreach (['rows', 'options', 'help', 'placeholder'] as $extra) {
          if (isset($field[$extra])) {
              $args[$extra] = $field[$extra];
          }
      }
      if ($type === 'checkbox') {
          $args['checked'] = old_raw($name, (string) ($row[$name] ?? '0')) === '1';
      } else {
          $args['value'] = old_raw($name, (string) ($row[$name] ?? ''));
      }
    ?>
    <div class="<?= $wide ? 'sm:col-span-2' : '' ?><?= $type === 'checkbox' ? ' rounded-lg border border-ink-100 bg-sunken/60 p-4' : '' ?>">
      <?php $f($args); ?>
    </div>
    <?php endforeach; ?>

    <?php if ($spec['sortable'] ?? true): ?>
    <?php $f(['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number',
              'value' => old_raw('sort_order', (string) ($row['sort_order'] ?? '0')),
              'help' => 'Lower numbers appear first.']); ?>
    <?php endif; ?>
    <?php $f(['name' => 'status', 'label' => 'Status', 'type' => 'select',
              'value' => old_raw('status', $row['status'] ?? 'published'),
              'options' => ['published' => 'Published', 'draft' => 'Draft']]); ?>
  </div>

  <div class="mt-7 flex gap-3">
    <button type="submit" class="inline-flex items-center h-11 px-7 rounded-lg bg-navy text-paper font-semibold hover:brightness-110 transition"><?= $row ? 'Save Changes' : 'Add ' . e($spec['title']) ?></button>
    <a href="<?= url($base) ?>" class="inline-flex items-center h-11 px-6 rounded-lg text-ink-700 font-medium hover:bg-sunken transition">Cancel</a>
  </div>
</form>
<?php clear_old(); ?>
