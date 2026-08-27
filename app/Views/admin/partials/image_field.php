<?php
/**
 * Upload field for a single image, with the current file shown and an explicit
 * way to remove it.
 *
 * expects: $name (the column), $label; optional $help, $current (stored path),
 *          $shape ('square' | 'wide' | 'portrait'), $accept
 *
 * The remove checkbox is only rendered when there is something to remove, and it
 * posts as <name>_remove so the controller can tell "clear this" apart from
 * "leave it alone" — an empty file input means the latter.
 */
$shape   = $shape ?? 'square';
$current = $current ?? null;
$ratio   = ['square' => '1/1', 'wide' => '16/9', 'portrait' => '3/4'][$shape] ?? '1/1';
?>
<?php App\Core\View::partial('admin/partials/field', [
    'name'   => $name,
    'label'  => $label,
    'type'   => 'file',
    'help'   => $help ?? null,
    'accept' => $accept ?? 'image/*',
]); ?>

<?php if ($current): ?>
<div class="mt-3 flex flex-wrap items-start gap-4">
  <img src="<?= upload_url($current) ?>" alt="Current image"
       class="h-24 rounded-lg object-cover border border-ink-100" style="aspect-ratio:<?= e($ratio) ?>">

  <div class="flex flex-col gap-2">
    <p class="text-xs text-ink-500">
      Choosing a new file replaces this one.
    </p>
    <label class="inline-flex items-center gap-2.5 text-sm text-ink-700 cursor-pointer">
      <input type="hidden" name="<?= e($name) ?>_remove" value="0">
      <input type="checkbox" name="<?= e($name) ?>_remove" value="1" class="w-[18px] h-[18px] accent-danger">
      <span>Remove this image</span>
    </label>
    <span class="text-xs text-ink-500">Takes effect when you save. The file is deleted from the server.</span>
  </div>
</div>
<?php endif; ?>
