<?php
/**
 * Shared admin form field.
 * expects: $name, $label; optional: $type (text|textarea|select|file|checkbox|datetime-local|password|number|email),
 *          $value, $required, $help, $options (for select), $rows, $accept, $checked, $placeholder
 */
$type = $type ?? 'text';
$value = $value ?? '';
$required = !empty($required);
$id = 'f_' . preg_replace('/[^a-z0-9_]/', '_', strtolower($name));
$inputCls = 'w-full rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15';
?>
<div class="flex <?= $type === 'checkbox' ? 'flex-row items-center gap-2.5' : 'flex-col gap-1.5' ?>">
<?php if ($type === 'checkbox'): ?>
  <input type="hidden" name="<?= e($name) ?>" value="0">
  <input id="<?= $id ?>" type="checkbox" name="<?= e($name) ?>" value="1" <?= !empty($checked) ? 'checked' : '' ?> class="w-[18px] h-[18px] accent-navy">
  <label for="<?= $id ?>" class="text-sm font-medium text-ink-700"><?= e($label) ?></label>
<?php else: ?>
  <label for="<?= $id ?>" class="text-sm font-medium text-ink-700"><?= e($label) ?><?= $required ? ' <span class="text-danger">*</span>' : '' ?></label>
  <?php if ($type === 'textarea'): ?>
  <textarea id="<?= $id ?>" name="<?= e($name) ?>" rows="<?= (int) ($rows ?? 4) ?>" <?= $required ? 'required' : '' ?>
            placeholder="<?= e($placeholder ?? '') ?>" class="<?= $inputCls ?> py-2.5 resize-y"><?= e((string) $value) ?></textarea>
  <?php elseif ($type === 'select'): ?>
  <select id="<?= $id ?>" name="<?= e($name) ?>" <?= $required ? 'required' : '' ?> class="<?= $inputCls ?> h-11">
    <?php foreach (($options ?? []) as $optValue => $optLabel): ?>
    <option value="<?= e((string) $optValue) ?>" <?= (string) $value === (string) $optValue ? 'selected' : '' ?>><?= e((string) $optLabel) ?></option>
    <?php endforeach; ?>
  </select>
  <?php elseif ($type === 'file'): ?>
  <input id="<?= $id ?>" type="file" name="<?= e($name) ?>" accept="<?= e($accept ?? 'image/*') ?>" <?= !empty($multiple) ? 'multiple' : '' ?>
         class="w-full text-sm text-ink-500 file:mr-3 file:h-10 file:px-4 file:rounded-lg file:border-0 file:bg-navy file:text-paper file:font-semibold file:text-sm file:cursor-pointer hover:file:brightness-110">
  <?php else: ?>
  <input id="<?= $id ?>" type="<?= e($type) ?>" name="<?= e($name) ?>" value="<?= e((string) $value) ?>" <?= $required ? 'required' : '' ?>
         placeholder="<?= e($placeholder ?? '') ?>" class="<?= $inputCls ?> h-11">
  <?php endif; ?>
  <?php if (!empty($help)): ?><span class="text-xs text-ink-500"><?= e($help) ?></span><?php endif; ?>
<?php endif; ?>
</div>
