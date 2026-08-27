<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Blog;

final class BlogController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/blogs/index', [
            'pageTitle' => 'Blogs',
            'paged'     => Blog::paginate($this->pageParam(), ADMIN_PER_PAGE, '1', [], 'published_at DESC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/blogs/form', ['pageTitle' => 'Add Blog Post', 'item' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/blogs/create');
        }
        if (Blog::findBy('slug', $data['slug'])) {
            $data['slug'] .= '-' . time();
        }
        if ($image = handle_upload('image', 'blogs')) {
            $data['image'] = $image;
        }
        Blog::create($data);
        ActivityLog::record('created', 'blog', $data['title']);
        clear_old();
        Session::flash('success', 'Blog post published to the website.');
        redirect('/admin/blogs');
    }

    public function edit(string $id): void
    {
        $item = Blog::find((int) $id);
        if (!$item) {
            Session::flash('error', 'Blog post not found.');
            redirect('/admin/blogs');
        }
        $this->adminView('admin/blogs/form', ['pageTitle' => 'Edit Blog Post', 'item' => $item]);
    }

    public function update(string $id): void
    {
        $item = Blog::find((int) $id);
        if (!$item) {
            redirect('/admin/blogs');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/blogs/edit/' . $id);
        }
        $existing = Blog::findBy('slug', $data['slug']);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            $data['slug'] .= '-' . time();
        }
        if ($image = handle_upload('image', 'blogs')) {
            delete_upload_set($item['image']);
            $data['image'] = $image;
        }
        Blog::update((int) $id, $data);
        ActivityLog::record('updated', 'blog', $data['title']);
        clear_old();
        Session::flash('success', 'Blog post updated.');
        redirect('/admin/blogs');
    }

    public function destroy(string $id): void
    {
        $item = Blog::find((int) $id);
        if ($item) {
            delete_upload_set($item['image']);
            Blog::delete((int) $id);
            ActivityLog::record('deleted', 'blog', $item['title']);
            Session::flash('success', 'Blog post deleted.');
        }
        redirect('/admin/blogs');
    }

    private function validated(): ?array
    {
        $data = [
            'title'        => $this->input('title'),
            'slug'         => slugify($this->input('slug') ?: $this->input('title')),
            'author'       => $this->input('author') ?: 'Pakistan Cambridge School',
            'excerpt'      => $this->input('excerpt'),
            'content'      => trim((string) ($_POST['content'] ?? '')),
            'published_at' => str_replace('T', ' ', $this->input('published_at')) ?: date('Y-m-d H:i:s'),
            'status'       => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['title']) < 3 || mb_strlen($data['content']) < 20) {
            keep_old($data);
            Session::flash('error', 'A post needs a title and body content (at least 20 characters).');
            return null;
        }
        if ($data['excerpt'] === '') {
            $data['excerpt'] = excerpt($data['content'], 200);
        }
        return $data;
    }
}
