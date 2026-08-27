<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;

final class MenuController extends AdminController
{
    /** The builder: menu tabs on top, structure left, add/edit form right. */
    public function index(): void
    {
        $menus = Menu::withCounts();
        if (!$menus) {
            Session::flash('error', 'No menus exist. Create one to begin.');
        }

        $activeId = (int) $this->queryParam('menu', (string) ($menus[0]['id'] ?? 0));
        $active = null;
        foreach ($menus as $menu) {
            if ((int) $menu['id'] === $activeId) {
                $active = $menu;
                break;
            }
        }
        if (!$active && $menus) {
            $active = $menus[0];
            $activeId = (int) $active['id'];
        }

        $editing = null;
        $editId = (int) $this->queryParam('edit', '0');
        if ($editId > 0) {
            $candidate = MenuItem::find($editId);
            if ($candidate && (int) $candidate['menu_id'] === $activeId) {
                $editing = $candidate;
            }
        }

        $this->adminView('admin/menu/index', [
            'pageTitle' => 'Menu Builder',
            'menus'     => $menus,
            'active'    => $active,
            'activeId'  => $activeId,
            'rows'      => $active ? MenuItem::flatten($activeId) : [],
            // Content pages only: system pages are already offered as built-in
            // destinations, and picking one here would link to /page/<slug>,
            // which is not where a system page is served from.
            'pages'     => Page::where('status = ? AND page_type = ?', ['published', 'content'], 'title ASC'),
            'editing'   => $editing,
        ]);
    }

    // ---------------------------------------------------------------- menus

    public function createMenu(): void
    {
        $name = $this->input('name');
        if (mb_strlen($name) < 2) {
            Session::flash('error', 'Give the menu a name of at least 2 characters.');
            redirect('/admin/menu');
        }
        $id = Menu::create(['name' => $name, 'slug' => Menu::uniqueSlug($name)]);
        ActivityLog::record('created', 'menu', 'Menu "' . $name . '"');
        Session::flash('success', 'Menu "' . $name . '" created. Add items to it on the right.');
        redirect('/admin/menu?menu=' . $id);
    }

    public function renameMenu(string $id): void
    {
        $menu = Menu::find((int) $id);
        $name = $this->input('name');
        if ($menu && mb_strlen($name) >= 2) {
            Menu::update((int) $id, ['name' => $name]);
            ActivityLog::record('updated', 'menu', 'Renamed menu to "' . $name . '"');
            Session::flash('success', 'Menu renamed.');
        } else {
            Session::flash('error', 'The menu name must be at least 2 characters.');
        }
        redirect('/admin/menu?menu=' . $id);
    }

    public function deleteMenu(string $id): void
    {
        $menu = Menu::find((int) $id);
        if (!$menu) {
            redirect('/admin/menu');
        }
        if (Menu::isProtected($menu)) {
            Session::flash('error', 'The Header Menu cannot be deleted — the site navigation depends on it.');
            redirect('/admin/menu?menu=' . $id);
        }
        Menu::delete((int) $id);
        ActivityLog::record('deleted', 'menu', 'Menu "' . $menu['name'] . '" and its items');
        Session::flash('success', 'Menu "' . $menu['name'] . '" deleted.');
        redirect('/admin/menu');
    }

    // ---------------------------------------------------------------- items

    /** Drag-and-drop save: order + nesting depth in one submit. */
    public function saveStructure(string $id): void
    {
        $menuId = (int) $id;
        $order = array_map('intval', (array) ($_POST['order'] ?? []));
        $depth = array_map('intval', (array) ($_POST['depth'] ?? []));

        if (!$order || count($order) !== count($depth)) {
            Session::flash('error', 'The menu structure could not be read. Please try again.');
            redirect('/admin/menu?menu=' . $menuId);
        }

        MenuItem::saveStructure($menuId, $order, $depth);
        ActivityLog::record('updated', 'menu', 'Rearranged menu structure');
        Session::flash('success', 'Menu structure saved.');
        redirect('/admin/menu?menu=' . $menuId);
    }

    /** Create or update a single item. */
    public function saveItem(string $id): void
    {
        $menuId = (int) $id;
        $itemId = (int) $this->input('id', '0');
        $label = $this->input('label');

        if (mb_strlen($label) < 2) {
            Session::flash('error', 'A menu label of at least 2 characters is required.');
            redirect('/admin/menu?menu=' . $menuId . ($itemId ? '&edit=' . $itemId : ''));
        }

        $target = $this->resolveTarget();
        if ($target === null) {
            redirect('/admin/menu?menu=' . $menuId . ($itemId ? '&edit=' . $itemId : ''));
        }

        $data = array_merge($target, [
            'label'   => $label,
            'new_tab' => isset($_POST['new_tab']) ? 1 : 0,
            'status'  => isset($_POST['is_active']) ? 'published' : 'draft',
        ]);

        if ($itemId > 0 && ($existing = MenuItem::find($itemId)) && (int) $existing['menu_id'] === $menuId) {
            MenuItem::update($itemId, $data);
            ActivityLog::record('updated', 'menu', $label);
            Session::flash('success', 'Menu item updated.');
        } else {
            $data['menu_id'] = $menuId;
            $data['parent_id'] = null;
            $data['sort_order'] = MenuItem::nextSortOrder($menuId);
            MenuItem::create($data);
            ActivityLog::record('created', 'menu', $label);
            Session::flash('success', 'Added to the end of the menu — drag it into place, then Save Structure.');
        }

        redirect('/admin/menu?menu=' . $menuId);
    }

    public function deleteItem(string $id): void
    {
        $item = MenuItem::find((int) $id);
        if (!$item) {
            redirect('/admin/menu');
        }
        $menuId = (int) $item['menu_id'];
        $children = MenuItem::count('parent_id = ?', [(int) $id]);

        MenuItem::delete((int) $id); // children cascade via the foreign key
        ActivityLog::record('deleted', 'menu', $item['label']);
        Session::flash(
            'success',
            $children > 0
                ? '"' . $item['label'] . '" and its ' . $children . ' sub-item(s) were removed.'
                : '"' . $item['label'] . '" removed from the menu.'
        );
        redirect('/admin/menu?menu=' . $menuId);
    }

    /**
     * Turn the single "Link to" select into stored columns.
     * Values arrive as "route:/programs", "page:12", "none" or "custom".
     *
     * @return array{link_type:string, route:string, page_id:?int, custom_url:string}|null
     */
    private function resolveTarget(): ?array
    {
        $blank = ['link_type' => 'route', 'route' => '/', 'page_id' => null, 'custom_url' => ''];
        $choice = $this->input('link_target');

        if ($choice === 'none') {
            return array_merge($blank, ['link_type' => 'none', 'route' => '']);
        }

        if ($choice === 'custom') {
            $url = $this->input('custom_url');
            if (filter_var($url, FILTER_VALIDATE_URL) === false && !str_starts_with($url, '/')) {
                Session::flash('error', 'Enter a full URL (https://…) or a site path beginning with a slash.');
                return null;
            }
            return array_merge($blank, ['link_type' => 'custom', 'route' => '', 'custom_url' => $url]);
        }

        if (str_starts_with($choice, 'page:')) {
            $pageId = (int) substr($choice, 5);
            if ($pageId <= 0 || !Page::find($pageId)) {
                Session::flash('error', 'That page no longer exists — choose another destination.');
                return null;
            }
            return array_merge($blank, ['link_type' => 'page', 'route' => '', 'page_id' => $pageId]);
        }

        if (str_starts_with($choice, 'route:')) {
            $route = substr($choice, 6);
            if (!isset(MenuItem::ROUTES[$route])) {
                Session::flash('error', 'Choose a valid destination for this menu item.');
                return null;
            }
            return array_merge($blank, ['route' => $route]);
        }

        Session::flash('error', 'Choose where this menu item should link to.');
        return null;
    }
}
