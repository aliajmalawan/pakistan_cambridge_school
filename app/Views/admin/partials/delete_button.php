<?php /* expects: $action (url path), optional $label, $confirm */ ?>
<form method="post" action="<?= url($action) ?>" class="inline"
      onsubmit="return confirm('<?= e($confirm ?? 'Delete this item permanently? This cannot be undone.') ?>');">
  <?= App\Core\Csrf::field() ?>
  <button type="submit" class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-danger hover:bg-danger/10 transition"><?= e($label ?? 'Delete') ?></button>
</form>
