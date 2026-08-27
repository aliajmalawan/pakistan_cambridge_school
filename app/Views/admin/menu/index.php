<?php
/** Current value of the "Link to" select for the item being edited. */
$currentTarget = 'route:/';
if ($editing) {
    $currentTarget = match ($editing['link_type']) {
        'page'   => 'page:' . (int) $editing['page_id'],
        'custom' => 'custom',
        'none'   => 'none',
        default  => 'route:' . $editing['route'],
    };
}
$isCustom = $editing && $editing['link_type'] === 'custom';
?>

<!-- Menus -->
<div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5 mb-6">
  <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
    <div>
      <h2 class="font-semibold">Menus</h2>
      <p class="text-sm text-ink-500 mt-0.5">Pick a menu to edit, or create another one.</p>
    </div>
    <form method="post" action="<?= url('/admin/menu/menus/create') ?>" class="flex gap-2">
      <?= App\Core\Csrf::field() ?>
      <label for="new-menu-name" class="sr-only">New menu name</label>
      <input id="new-menu-name" type="text" name="name" placeholder="New menu name…" required
             class="h-9 w-44 rounded-lg border border-ink-300 bg-sunken px-3 text-sm focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
      <button type="submit" class="inline-flex items-center h-9 px-4 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition shrink-0">+ Create</button>
    </form>
  </div>

  <div class="flex gap-2 flex-wrap">
    <?php foreach ($menus as $menu): ?>
    <?php $on = (int) $menu['id'] === $activeId; ?>
    <a href="<?= url('/admin/menu?menu=' . $menu['id']) ?>"
       class="inline-flex items-center gap-2 h-9 px-4 rounded-full text-sm font-medium border transition
       <?= $on ? 'bg-navy text-paper border-navy' : 'border-ink-300 text-ink-700 hover:bg-sunken' ?>"
       <?= $on ? 'aria-current="true"' : '' ?>>
      <?= e($menu['name']) ?>
      <span class="tabular <?= $on ? 'text-paper/60' : 'text-ink-500' ?>"><?= (int) $menu['item_count'] ?></span>
    </a>
    <?php endforeach; ?>
  </div>

  <p class="text-xs text-ink-500 mt-4 leading-relaxed">
    <span class="font-medium text-ink-700">Header Menu</span> is the navigation bar at the top of every page and cannot be deleted.
    The two <span class="font-medium text-ink-700">Footer</span> menus are the link columns in the website footer.
    Any menu you create yourself can be used later.
  </p>
</div>

<?php if ($active): ?>
<div class="grid lg:grid-cols-[7fr_5fr] gap-6 items-start">

  <!-- ============ Structure ============ -->
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
    <div class="flex items-start justify-between gap-3 flex-wrap mb-4">
      <div class="min-w-0">
        <h2 class="font-semibold">Structure</h2>
        <p class="text-sm text-ink-500 mt-0.5 truncate"><?= e($active['description'] ?: $active['name']) ?></p>
      </div>
      <div class="flex gap-2 shrink-0">
        <form method="post" action="<?= url('/admin/menu/menus/rename/' . $activeId) ?>" class="flex gap-1.5">
          <?= App\Core\Csrf::field() ?>
          <label for="rename-menu" class="sr-only">Rename this menu</label>
          <input id="rename-menu" type="text" name="name" value="<?= e($active['name']) ?>"
                 class="h-8 w-36 rounded-lg border border-ink-300 bg-sunken px-2.5 text-sm focus:outline-none focus:border-navy">
          <button type="submit" class="inline-flex items-center h-8 px-3 rounded-lg border border-ink-300 text-xs font-semibold hover:bg-sunken transition">Rename</button>
        </form>
        <?php if (!App\Models\Menu::isProtected($active)): ?>
        <button type="submit" form="delete-menu-form"
                class="inline-flex items-center h-8 px-3 rounded-lg text-xs font-semibold text-danger hover:bg-danger/10 transition">Delete Menu</button>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!$rows): ?>
    <div class="text-center py-14 border-[1.5px] border-dashed border-ink-300 rounded-lg">
      <p class="font-heading font-semibold">This menu is empty</p>
      <p class="text-sm text-ink-500 mt-1">Add the first item using the form on the right.</p>
    </div>
    <?php else: ?>

    <p class="text-xs text-ink-500 mb-3 leading-relaxed">
      Drag <span class="font-semibold">⠿</span> to reorder ·
      <span class="font-semibold">⇥</span> makes an item a sub-item of the one above ·
      <span class="font-semibold">⇤</span> moves it back out · then press <span class="font-semibold">Save Structure</span>.
    </p>

    <form method="post" action="<?= url('/admin/menu/' . $activeId . '/structure') ?>" id="structure-form">
      <?= App\Core\Csrf::field() ?>
      <div id="menu-tree" class="space-y-1.5">
        <?php foreach ($rows as $row): ?>
        <?php $orphan = $row['link_type'] === 'page' && $row['page_title'] === null; ?>
        <div class="menu-row flex items-center gap-2 rounded-lg border border-ink-100 bg-white px-3 py-2.5 transition
                    <?= $row['status'] === 'published' ? '' : 'opacity-55' ?>"
             draggable="true" data-id="<?= (int) $row['id'] ?>" data-depth="<?= (int) $row['depth'] ?>"
             style="margin-left: <?= (int) $row['depth'] * 26 ?>px;">

          <span class="drag-grip cursor-grab select-none text-ink-300 hover:text-ink-500 shrink-0" title="Drag to reorder" aria-hidden="true">⠿</span>

          <span class="min-w-0 grow">
            <span class="text-sm font-medium"><?= e($row['label']) ?></span>
            <span class="block text-xs <?= $orphan ? 'text-danger' : 'text-ink-500' ?> truncate">
              <?= e(App\Models\MenuItem::describeTarget($row)) ?><?= $row['new_tab'] ? ' ↗' : '' ?><?= $row['status'] === 'published' ? '' : ' · hidden' ?>
            </span>
          </span>

          <button type="button" class="menu-outdent w-7 h-7 rounded text-ink-500 hover:bg-ink-100 transition shrink-0" title="Move out a level" aria-label="Move <?= e($row['label']) ?> out one level">⇤</button>
          <button type="button" class="menu-indent w-7 h-7 rounded text-ink-500 hover:bg-ink-100 transition shrink-0" title="Make sub-item" aria-label="Make <?= e($row['label']) ?> a sub-item">⇥</button>
          <a href="<?= url('/admin/menu?menu=' . $activeId . '&edit=' . $row['id']) ?>"
             class="inline-flex items-center h-7 px-2.5 rounded-lg text-xs font-semibold text-navy hover:bg-navy-50 transition shrink-0">Edit</a>
          <button type="button" class="row-delete w-7 h-7 rounded text-danger hover:bg-danger/10 transition shrink-0"
                  data-id="<?= (int) $row['id'] ?>" data-label="<?= e($row['label']) ?>" aria-label="Delete <?= e($row['label']) ?>">✕</button>

          <input type="hidden" name="order[]" value="<?= (int) $row['id'] ?>">
          <input type="hidden" name="depth[]" value="<?= (int) $row['depth'] ?>">
        </div>
        <?php endforeach; ?>
      </div>

      <button type="submit" class="mt-4 inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition">Save Structure</button>
      <span id="structure-dirty" class="hidden ml-3 text-xs font-medium text-gold-deep">Unsaved changes</span>
    </form>
    <?php endif; ?>
  </div>

  <!-- ============ Add / Edit item ============ -->
  <div class="bg-white rounded-xl border border-ink-100 shadow-kds-sm p-5">
    <div class="flex items-center justify-between gap-3 mb-4">
      <h2 class="font-semibold"><?= $editing ? 'Edit Item' : 'Add Item' ?></h2>
      <?php if ($editing): ?>
      <a href="<?= url('/admin/menu?menu=' . $activeId) ?>" class="inline-flex items-center h-8 px-3 rounded-lg border border-ink-300 text-xs font-semibold hover:bg-sunken transition">Cancel</a>
      <?php endif; ?>
    </div>

    <form method="post" action="<?= url('/admin/menu/' . $activeId . '/item') ?>" class="grid gap-4">
      <?= App\Core\Csrf::field() ?>
      <input type="hidden" name="id" value="<?= e((string) ($editing['id'] ?? '')) ?>">

      <div class="flex flex-col gap-1.5">
        <label for="item-label" class="text-sm font-medium text-ink-700">Label <span class="text-danger">*</span></label>
        <input id="item-label" name="label" required value="<?= e($editing['label'] ?? '') ?>" placeholder="Admissions"
               class="h-11 rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
        <span class="text-xs text-ink-500">The text visitors see. Keep it to one or two words.</span>
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="link-target" class="text-sm font-medium text-ink-700">Link to <span class="text-danger">*</span></label>
        <select id="link-target" name="link_target"
                class="h-11 rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
          <optgroup label="Built-in pages">
            <?php foreach (App\Models\MenuItem::ROUTES as $path => $name): ?>
            <option value="route:<?= e($path) ?>" <?= $currentTarget === 'route:' . $path ? 'selected' : '' ?>><?= e($name) ?></option>
            <?php endforeach; ?>
          </optgroup>
          <?php if ($pages): ?>
          <optgroup label="Your pages">
            <?php foreach ($pages as $p): ?>
            <option value="page:<?= (int) $p['id'] ?>" <?= $currentTarget === 'page:' . $p['id'] ? 'selected' : '' ?>><?= e($p['title']) ?></option>
            <?php endforeach; ?>
          </optgroup>
          <?php endif; ?>
          <optgroup label="Other">
            <option value="none" <?= $currentTarget === 'none' ? 'selected' : '' ?>>Dropdown heading (no link)</option>
            <option value="custom" <?= $isCustom ? 'selected' : '' ?>>Custom URL…</option>
          </optgroup>
        </select>
        <span class="text-xs text-ink-500">Choosing a page fills the label in for you when it is still blank.</span>
      </div>

      <div class="flex flex-col gap-1.5" id="custom-url-group" <?= $isCustom ? '' : 'hidden' ?>>
        <label for="custom-url" class="text-sm font-medium text-ink-700">Custom URL</label>
        <input id="custom-url" name="custom_url" value="<?= e($editing['custom_url'] ?? '') ?>" placeholder="https://…  or  /some/path"
               class="h-11 rounded border border-ink-300 bg-sunken px-3 focus:outline-none focus:border-navy focus:ring-[3px] focus:ring-navy/15">
        <span class="text-xs text-ink-500">A full address, or a path on this site starting with a slash.</span>
      </div>

      <div class="flex flex-col gap-2.5 pt-1">
        <label class="flex items-center gap-2.5 text-sm text-ink-700">
          <input type="checkbox" name="new_tab" value="1" <?= !empty($editing['new_tab']) ? 'checked' : '' ?> class="w-[18px] h-[18px] accent-navy">
          Open in a new tab ↗
        </label>
        <label class="flex items-center gap-2.5 text-sm text-ink-700">
          <input type="checkbox" name="is_active" value="1" <?= (!$editing || $editing['status'] === 'published') ? 'checked' : '' ?> class="w-[18px] h-[18px] accent-navy">
          Visible on the website
        </label>
      </div>

      <div class="flex gap-2.5 pt-1">
        <button type="submit" class="inline-flex items-center h-10 px-5 rounded-lg bg-navy text-paper text-sm font-semibold hover:brightness-110 transition"><?= $editing ? 'Update Item' : 'Add Item' ?></button>
        <?php if ($editing): ?>
        <button type="submit" form="delete-item-form"
                class="inline-flex items-center h-10 px-4 rounded-lg text-sm font-semibold text-danger hover:bg-danger/10 transition">Delete</button>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<?php /* Out-of-band forms — never nested inside another form */ ?>
<?php if (!App\Models\Menu::isProtected($active)): ?>
<form id="delete-menu-form" method="post" action="<?= url('/admin/menu/menus/delete/' . $activeId) ?>" class="hidden"
      onsubmit="return confirm('Delete the menu “<?= e(addslashes($active['name'])) ?>” and all of its items?');">
  <?= App\Core\Csrf::field() ?>
</form>
<?php endif; ?>

<?php if ($editing): ?>
<form id="delete-item-form" method="post" action="<?= url('/admin/menu/item/delete/' . $editing['id']) ?>" class="hidden"
      onsubmit="return confirm('Delete “<?= e(addslashes($editing['label'])) ?>” and any sub-items beneath it?');">
  <?= App\Core\Csrf::field() ?>
</form>
<?php endif; ?>

<form id="row-delete-form" method="post" action="" class="hidden">
  <?= App\Core\Csrf::field() ?>
</form>
<?php endif; ?>

<script>
(function () {
  "use strict";

  var tree = document.getElementById('menu-tree');
  var deleteForm = document.getElementById('row-delete-form');
  var deleteBase = <?= json_encode(url('/admin/menu/item/delete/')) ?>;

  // Per-row delete
  document.querySelectorAll('.row-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var label = btn.getAttribute('data-label') || 'this item';
      if (!confirm('Delete “' + label + '” and any sub-items beneath it?')) return;
      deleteForm.action = deleteBase + btn.getAttribute('data-id');
      deleteForm.submit();
    });
  });

  if (!tree) return;

  var INDENT = 26;
  var dragging = null;
  var dirty = document.getElementById('structure-dirty');

  var rows = function () {
    return Array.prototype.slice.call(tree.querySelectorAll('.menu-row'));
  };
  var depthOf = function (row) {
    return parseInt(row.getAttribute('data-depth') || '0', 10);
  };
  var setDepth = function (row, depth) {
    row.setAttribute('data-depth', String(depth));
    row.style.marginLeft = (depth * INDENT) + 'px';
    row.querySelector('input[name="depth[]"]').value = String(depth);
  };

  // An item may only ever be one level deeper than the row above it.
  var clamp = function () {
    var previous = -1;
    rows().forEach(function (row) {
      var depth = Math.max(0, Math.min(depthOf(row), previous + 1));
      setDepth(row, depth);
      previous = depth;
    });
  };

  var markDirty = function () {
    if (dirty) dirty.classList.remove('hidden');
  };

  rows().forEach(function (row) {
    row.addEventListener('dragstart', function () {
      dragging = row;
      setTimeout(function () { row.classList.add('opacity-40', 'ring-2', 'ring-gold'); }, 0);
    });
    row.addEventListener('dragend', function () {
      row.classList.remove('opacity-40', 'ring-2', 'ring-gold');
      dragging = null;
      clamp();
      markDirty();
    });
    row.querySelector('.menu-indent').addEventListener('click', function () {
      setDepth(row, depthOf(row) + 1);
      clamp();
      markDirty();
    });
    row.querySelector('.menu-outdent').addEventListener('click', function () {
      setDepth(row, Math.max(0, depthOf(row) - 1));
      clamp();
      markDirty();
    });
  });

  tree.addEventListener('dragover', function (e) {
    e.preventDefault();
    if (!dragging) return;
    var before = null;
    rows().forEach(function (row) {
      if (row === dragging || before !== null) return;
      var box = row.getBoundingClientRect();
      if (e.clientY < box.top + box.height / 2) before = row;
    });
    if (before) {
      tree.insertBefore(dragging, before);
    } else {
      tree.appendChild(dragging);
    }
  });

  var form = document.getElementById('structure-form');
  if (form) form.addEventListener('submit', clamp);

  // Fill the label from the chosen page, and reveal the custom URL field
  var select = document.getElementById('link-target');
  var customGroup = document.getElementById('custom-url-group');
  var label = document.getElementById('item-label');
  if (select && customGroup) {
    select.addEventListener('change', function () {
      customGroup.hidden = select.value !== 'custom';
      if (select.value === 'custom') {
        document.getElementById('custom-url').focus();
        return;
      }
      if (label && label.value.trim() === '' && select.value !== 'none') {
        label.value = select.options[select.selectedIndex].text;
      }
    });
  }
})();
</script>
