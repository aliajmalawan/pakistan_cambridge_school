<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Blog;

final class BlogController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $paged = Blog::paginate($page, 9, 'status = ? AND published_at <= NOW()', ['published'], 'published_at DESC');

        $this->view('blog-index', [
            'pageTitle' => 'Blog — Pakistan Cambridge School Hafizabad',
            'metaDescription' => 'Stories, updates and notes from Pakistan Cambridge School Hafizabad.',
            'paged' => $paged,
        ]);
    }

    public function show(string $slug): void
    {
        $post = Blog::publishedBySlug($slug);
        if (!$post) {
            http_response_code(404);
            $this->view('errors/404', ['code' => 404]);
            return;
        }

        $recent = array_values(array_filter(
            Blog::latest(4),
            fn($p) => $p['id'] !== $post['id']
        ));

        $this->view('blog-post', [
            'pageTitle' => $post['title'] . ' — Pakistan Cambridge School Blog',
            'metaDescription' => $post['excerpt'] ?: excerpt($post['content'], 160),
            'post' => $post,
            'related' => array_slice($recent, 0, 3),
        ]);
    }
}
