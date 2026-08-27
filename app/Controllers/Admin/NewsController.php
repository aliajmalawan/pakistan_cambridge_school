<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\News;

final class NewsController extends AdminController
{
    public function index(): void
    {
        $category = $this->queryParam('category');
        $condition = '1';
        $params = [];
        if (isset(News::CATEGORIES[$category])) {
            $condition = 'category = ?';
            $params = [$category];
        } else {
            $category = '';
        }
        $this->adminView('admin/news/index', [
            'pageTitle' => 'News & Notices',
            'category'  => $category,
            'paged'     => News::paginate($this->pageParam(), ADMIN_PER_PAGE, $condition, $params, 'published_at DESC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/news/form', ['pageTitle' => 'Add Post', 'item' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/news/create');
        }
        if (News::findBy('slug', $data['slug'])) {
            $data['slug'] .= '-' . time();
        }
        if ($image = handle_upload('image', 'news')) {
            $data['image'] = $image;
        }
        News::create($data);
        ActivityLog::record('created', 'news', $data['title']);
        clear_old();
        Session::flash('success', 'Post published to the website.');
        redirect('/admin/news');
    }

    public function edit(string $id): void
    {
        $item = News::find((int) $id);
        if (!$item) {
            Session::flash('error', 'Post not found.');
            redirect('/admin/news');
        }
        $this->adminView('admin/news/form', ['pageTitle' => 'Edit Post', 'item' => $item]);
    }

    public function update(string $id): void
    {
        $item = News::find((int) $id);
        if (!$item) {
            redirect('/admin/news');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/news/edit/' . $id);
        }
        $existing = News::findBy('slug', $data['slug']);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            $data['slug'] .= '-' . time();
        }
        if ($image = handle_upload('image', 'news')) {
            delete_upload_set($item['image']);
            $data['image'] = $image;
        }
        News::update((int) $id, $data);
        ActivityLog::record('updated', 'news', $data['title']);
        clear_old();
        Session::flash('success', 'Post updated.');
        redirect('/admin/news');
    }

    public function destroy(string $id): void
    {
        $item = News::find((int) $id);
        if ($item) {
            delete_upload_set($item['image']);
            News::delete((int) $id);
            ActivityLog::record('deleted', 'news', $item['title']);
            Session::flash('success', 'Post deleted.');
        }
        redirect('/admin/news');
    }

    private function validated(): ?array
    {
        $category = $this->input('category');
        $data = [
            'title'        => $this->input('title'),
            'slug'         => slugify($this->input('slug') ?: $this->input('title')),
            'category'     => isset(News::CATEGORIES[$category]) ? $category : 'news',
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
