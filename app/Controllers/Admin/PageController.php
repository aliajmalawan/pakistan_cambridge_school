<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\ImageService;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Page;

final class PageController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/pages/index', [
            'pageTitle'    => 'Pages',
            'contentPages' => Page::contentPages(),
            'systemPages'  => Page::systemPages(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/form', ['pageTitle' => 'Add Page', 'page' => null]);
    }

    public function store(): void
    {
        $data = $this->validated(false);
        if ($data === null) {
            redirect('/admin/pages/create');
        }
        if (Page::findBy('slug', $data['slug'])) {
            keep_old($data);
            Session::flash('error', 'A page with slug "' . $data['slug'] . '" already exists.');
            redirect('/admin/pages/create');
        }
        if ($image = handle_upload('image', 'pages', ImageService::WIDTH_HERO)) {
            $data['image'] = $image;
        }
        $data['page_type'] = 'content';
        Page::create($data);

        clear_old();
        ActivityLog::record('created', 'page', $data['title']);
        Session::flash('success', 'Page created. It is now live at /page/' . $data['slug']);
        redirect('/admin/pages');
    }

    public function edit(string $id): void
    {
        $page = Page::find((int) $id);
        if (!$page) {
            Session::flash('error', 'Page not found.');
            redirect('/admin/pages');
        }
        $this->adminView('admin/pages/form', [
            'pageTitle' => (Page::isSystem($page) ? 'Edit System Page — ' : 'Edit Page — ') . $page['title'],
            'page'      => $page,
        ]);
    }

    public function update(string $id): void
    {
        $page = Page::find((int) $id);
        if (!$page) {
            redirect('/admin/pages');
        }
        $isSystem = Page::isSystem($page);

        $data = $this->validated($isSystem);
        if ($data === null) {
            redirect('/admin/pages/edit/' . $id);
        }

        if ($isSystem) {
            // A route owns this page: its slug, ordering and visibility are not
            // the admin's to change, only the words on it.
            unset($data['slug'], $data['sort_order']);
            $data['status'] = 'published';
        } else {
            $existing = Page::findBy('slug', $data['slug']);
            if ($existing && (int) $existing['id'] !== (int) $id) {
                keep_old($data);
                Session::flash('error', 'Another page already uses the slug "' . $data['slug'] . '".');
                redirect('/admin/pages/edit/' . $id);
            }
        }

        if ($image = handle_upload('image', 'pages', ImageService::WIDTH_HERO)) {
            delete_upload_set($page['image']);
            $data['image'] = $image;
        }

        Page::update((int) $id, $data);
        clear_old();
        ActivityLog::record('updated', 'page', $data['title']);
        Session::flash('success', 'Page updated.');
        redirect('/admin/pages');
    }

    public function destroy(string $id): void
    {
        $page = Page::find((int) $id);
        if (!$page) {
            redirect('/admin/pages');
        }
        if (Page::isSystem($page)) {
            Session::flash('error', 'System pages cannot be deleted — a route depends on this page.');
            redirect('/admin/pages');
        }

        delete_upload_set($page['image']);
        Page::delete((int) $id);
        ActivityLog::record('deleted', 'page', $page['title']);
        Session::flash('success', 'Page "' . $page['title'] . '" deleted.');
        redirect('/admin/pages');
    }

    /** @param bool $isSystem system pages have no slug and no required body */
    private function validated(bool $isSystem): ?array
    {
        $data = [
            'title'            => $this->input('title'),
            'lede'             => $this->input('lede'),
            'content'          => trim((string) ($_POST['content'] ?? '')),
            'meta_description' => $this->input('meta_description'),
            'status'           => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];

        if (!$isSystem) {
            $data['slug']       = slugify($this->input('slug') ?: $this->input('title'));
            $data['sort_order'] = (int) $this->input('sort_order', '0');
        }

        $errors = [];
        if (mb_strlen($data['title']) < 3) {
            $errors[] = 'A page heading of at least 3 characters is required.';
        }
        if (!$isSystem && mb_strlen($data['content']) < 20) {
            $errors[] = 'A content page needs body text of at least 20 characters.';
        }

        if ($errors) {
            keep_old($data);
            Session::flash('error', implode(' ', $errors));
            return null;
        }
        return $data;
    }
}
