<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\AcademicCalendar;
use App\Models\AcademicFramework;
use App\Models\Blog;
use App\Models\CalendarEvent;
use App\Models\ClassSubject;
use App\Models\CoreValue;
use App\Models\Download;
use App\Models\Faculty;
use App\Models\FeeNote;
use App\Models\FeeStructure;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\GradingScale;
use App\Models\Leadership;
use App\Models\Milestone;
use App\Models\News;
use App\Models\Page;
use App\Models\Program;
use App\Models\Testimonial;

final class PublicController extends Controller
{
    /** Render a CMS content page at /page/{slug}. */
    public function content(string $slug): void
    {
        $page = Page::published($slug);
        if (!$page) {
            http_response_code(404);
            $this->view('errors/404', ['code' => 404]);
            return;
        }

        // 'about' and 'facilities' are content pages but still have their own
        // rich pageData() cases — route them through the same switch as the
        // system pages, or that data never attaches.
        $route = match ($slug) {
            'about' => '/about',
            'facilities' => '/facilities',
            default => '',
        };
        $this->view('page', $this->pageData($page, 'content', $route));
    }

    /** Root aliases for common CMS pages, so /about and /facilities are clean public URLs. */
    public function contentRoot(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $base = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        if ($base !== '' && str_starts_with($uri, $base)) $uri = substr($uri, strlen($base));
        $slug = trim($uri, '/');
        $this->content($slug);
    }

    /** Render a route-driven public page. The router passes its one URL segment. */
    public function routePage(string $slug = ''): void
    {
        if ($slug === '') {
            $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
            $base = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
            if ($base !== '' && str_starts_with($uri, $base)) {
                $uri = substr($uri, strlen($base));
            }
            $slug = trim($uri, '/');
        }
        $path = '/' . trim($slug, '/');
        $map = [
            'vision-mission'   => '/vision-mission',
            'leadership'      => '/leadership',
            'academics'       => '/academics',
            'academic-calendar' => '/academic-calendar',
            'programs'        => '/programs',
            'fees'            => '/fees',
            'faculty'         => '/faculty',
            'news'            => '/news',
            'gallery'         => '/gallery',
            'admissions'      => '/admissions',
            'downloads'       => '/downloads',
            'contact'         => '/contact',
            'search'          => '/search',
        ];

        $route = $map[$slug] ?? null;
        if ($route === null) {
            // A root-level CMS page is allowed to have its own slug as a shortcut.
            $page = Page::published($slug);
            if ($page) {
                $this->view('page', $this->pageData($page, 'content'));
                return;
            }
            http_response_code(404);
            $this->view('errors/404', ['code' => 404]);
            return;
        }

        $page = Page::forRoute($route);
        if (($page['status'] ?? 'published') === 'draft') {
            http_response_code(404);
            $this->view('errors/404', ['code' => 404]);
            return;
        }

        $data = $this->pageData($page, 'system', $route);
        $this->view('page', $data);
    }


    /** XML sitemap — every public URL a search engine should crawl. */
    public function sitemap(): void
    {
        $urls = [];
        $add = function (string $path, string $freq = 'monthly', float $priority = 0.6) use (&$urls) {
            $urls[] = ['loc' => url($path), 'freq' => $freq, 'priority' => $priority];
        };

        $add('/', 'weekly', 1.0);
        foreach ([
            '/about', '/vision-mission', '/leadership', '/faculty',
            '/academics', '/academic-calendar', '/programs', '/fees', '/facilities', '/rules',
            '/admissions', '/gallery', '/downloads', '/news', '/blogs', '/contact',
        ] as $path) {
            $add($path, 'monthly', 0.7);
        }

        foreach (Blog::latest(500) as $post) {
            $add('/blogs/' . $post['slug'], 'monthly', 0.5);
        }

        // 'about', 'facilities' and 'rules' are content pages but are already
        // listed above under their own clean root URL — /page/<slug> would
        // duplicate them.
        $rootAliased = ['about', 'facilities', 'rules'];
        foreach (Page::contentPages() as $page) {
            if (($page['status'] ?? '') === 'published' && !in_array($page['slug'], $rootAliased, true)) {
                $add('/page/' . $page['slug'], 'monthly', 0.5);
            }
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo "  <url>\n";
            echo '    <loc>' . e($u['loc']) . "</loc>\n";
            echo '    <changefreq>' . $u['freq'] . "</changefreq>\n";
            echo '    <priority>' . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>';
    }

    /** Stream a published download or redirect to its external URL. */
    public function download(string $id): void
    {
        $item = Download::first('id = ? AND status = ?', [(int) $id, 'published']);
        if (!$item) {
            http_response_code(404);
            $this->view('errors/404', ['code' => 404]);
            return;
        }

        if (!empty($item['external_url'])) {
            redirect($item['external_url']);
        }

        if (!empty($item['file_path'])) {
            $file = UPLOAD_PATH . '/' . ltrim($item['file_path'], '/');
            if (is_file($file)) {
                Download::registerHit((int) $item['id']);
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        }

        // A published placeholder without a file should remain usable rather than 404.
        $this->view('page', $this->pageData([
            'title' => $item['title'],
            'lede' => $item['description'] ?? '',
            'content' => '<p>This document has not been uploaded yet. Please contact the school office for a printed or digital copy.</p>',
            'meta_description' => $item['description'] ?? '',
            'status' => 'published',
        ], 'content'));
    }

    private function pageData(array $page, string $kind, string $route = ''): array
    {
        $data = [
            'page' => $page,
            'pageTitle' => $page['title'] ?? 'Pakistan Cambridge School',
            'metaDescription' => $page['meta_description'] ?? '',
            'pageKind' => $kind,
            'route' => $route,
            'programs' => [],
            'values' => [],
            'leadership' => [],
            'faculty' => [],
            'news' => [],
            'albums' => [],
            'galleryImages' => [],
            'academicFramework' => [],
            'academicCalendar' => [],
            'calendarEvents' => [],
            'gradingScale' => [],
            'feeStructure' => [],
            'feeConcession' => [],
            'feePayment' => [],
            'downloads' => [],
            'milestones' => [],
            'testimonials' => [],
            'classSubjects' => [],
            'facilities' => [],
        ];

        switch ($route) {
            case '/vision-mission':
                $data['values'] = CoreValue::published();
                $data['leadership'] = Leadership::withMessages(2);
                break;
            case '/leadership':
                $data['leadership'] = Leadership::published();
                break;
            case '/academics':
                $data['academicFramework'] = AcademicFramework::published();
                $data['gradingScale'] = GradingScale::published();
                $data['academicCalendar'] = AcademicCalendar::published();
                break;
            case '/academic-calendar':
                $session = CalendarEvent::currentSession();
                $events = $session ? CalendarEvent::forSession($session) : [];
                $data['calendarEvents'] = $events;
                $data['calendarSession'] = $session;
                $data['calendarSessions'] = CalendarEvent::sessions();
                $data['calendarUpcoming'] = $session ? CalendarEvent::upcoming($session, 3) : [];
                $data['calendarByMonth'] = CalendarEvent::byMonth($events);
                $data['calendarSpan'] = CalendarEvent::span($events);
                $data['calendarTypeCounts'] = $session ? CalendarEvent::typeCounts($session) : [];
                $data['academicCalendar'] = AcademicCalendar::published();
                break;
            case '/programs':
                $data['programs'] = Program::published();
                $data['classSubjects'] = ClassSubject::published();
                break;
            case '/fees':
                $data['feeStructure'] = FeeStructure::published();
                $data['feeConcession'] = FeeNote::panel('concession');
                $data['feePayment'] = FeeNote::panel('payment');
                break;
            case '/faculty':
                $data['faculty'] = Faculty::published();
                break;
            case '/news':
                $data['news'] = News::latest(30);
                break;
            case '/gallery':
                $data['albums'] = GalleryAlbum::published();
                $data['galleryImages'] = GalleryImage::allPublished();
                break;
            case '/downloads':
                $data['downloads'] = Download::grouped();
                break;
            case '/about':
                $data['milestones'] = Milestone::published();
                $data['leadership'] = Leadership::withMessages(2);
                $data['values'] = CoreValue::published();
                break;
            case '/facilities':
                $data['facilities'] = \App\Models\Facility::published();
                break;
            case '/contact':
                break;
            case '/admissions':
                $data['programs'] = Program::published();
                break;
            case '/search':
                $term = trim((string) ($_GET['q'] ?? ''));
                $data['searchTerm'] = $term;
                $data['searchResults'] = $term !== '' ? $this->search($term) : [];
                break;
        }

        return $data;
    }

    private function search(string $term): array
    {
        $like = '%' . $term . '%';
        $results = [];

        foreach (Database::query(
            'SELECT title, slug, content, page_type, route FROM pages
             WHERE status = ? AND (title LIKE ? OR content LIKE ?)
             ORDER BY sort_order ASC, title ASC LIMIT 30',
            ['published', $like, $like]
        )->fetchAll() as $row) {
            $href = ($row['page_type'] ?? 'content') === 'system' && !empty($row['route'])
                ? url($row['route'])
                : url('/page/' . $row['slug']);
            $results[] = [
                'title' => $row['title'],
                'excerpt' => excerpt($row['content'] ?? '', 170),
                'href' => $href,
                'type' => 'Page',
            ];
        }

        foreach (News::latest(30) as $row) {
            $haystack = ($row['title'] ?? '') . ' ' . ($row['content'] ?? '');
            if (stripos($haystack, $term) !== false) {
                $results[] = [
                    'title' => $row['title'],
                    'excerpt' => excerpt($row['content'] ?? '', 170),
                    'href' => url('/news'),
                    'type' => 'News',
                ];
            }
        }

        return array_slice($results, 0, 40);
    }
}
