<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;

/**
 * Builds the document head: title, meta, canonical, Open Graph, Twitter cards
 * and JSON-LD structured data. Every public page passes through here.
 */
final class Seo
{
    private string $title = '';
    private string $description = '';
    private string $canonical = '';
    private ?string $image = null;
    private string $type = 'website';
    private array $article = [];
    private array $breadcrumbs = [];
    private bool $noindex = false;

    public static function make(): self
    {
        return new self();
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function description(string $description): self
    {
        $this->description = excerpt($description, 158);
        return $this;
    }

    /** @param string $path site-relative path, e.g. /news/slug */
    public function canonical(string $path): self
    {
        $this->canonical = url($path);
        return $this;
    }

    public function image(?string $uploadPath): self
    {
        $this->image = $uploadPath ? upload_url($uploadPath) : null;
        return $this;
    }

    public function article(string $publishedAt, ?string $modifiedAt = null, string $section = 'News'): self
    {
        $this->type = 'article';
        $this->article = [
            'published' => date('c', strtotime($publishedAt)),
            'modified'  => date('c', strtotime($modifiedAt ?: $publishedAt)),
            'section'   => $section,
        ];
        return $this;
    }

    /** @param array<string,string> $crumbs label => site-relative path ('' = current page) */
    public function breadcrumbs(array $crumbs): self
    {
        $this->breadcrumbs = $crumbs;
        return $this;
    }

    public function noindex(): self
    {
        $this->noindex = true;
        return $this;
    }

    private function siteName(): string
    {
        return Setting::value('site_name', APP_NAME);
    }

    private function fullTitle(): string
    {
        $site = $this->siteName();
        if ($this->title === '' || $this->title === $site) {
            return $site;
        }
        return $this->title . ' — ' . $site;
    }

    private function ogImage(): string
    {
        return $this->image ?: logo_url(512);
    }

    /** Organization + WebSite schema — emitted on every page. */
    private function organizationSchema(): array
    {
        $sameAs = array_values(array_filter([
            Setting::value('facebook'),
            Setting::value('youtube'),
        ]));

        $org = [
            '@type'       => 'EducationalOrganization',
            '@id'         => url('/') . '#organization',
            'name'        => $this->siteName(),
            'alternateName' => Setting::value('site_name_ur'),
            'url'         => url('/'),
            'logo'        => logo_url(512),
            'description' => Setting::value('mission'),
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => Setting::value('address'),
                'addressLocality' => 'Hafizabad',
                'addressRegion'   => 'Punjab',
                'addressCountry'  => 'PK',
            ],
        ];
        if ($phone = Setting::value('phone')) {
            $org['telephone'] = $phone;
        }
        if ($email = Setting::value('email')) {
            $org['email'] = $email;
        }
        if ($sameAs) {
            $org['sameAs'] = $sameAs;
        }
        return $org;
    }

    private function breadcrumbSchema(): ?array
    {
        if (!$this->breadcrumbs) {
            return null;
        }
        $items = [];
        $position = 1;
        foreach ($this->breadcrumbs as $label => $path) {
            $entry = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $label,
            ];
            if ($path !== '') {
                $entry['item'] = url($path);
            }
            $items[] = $entry;
        }
        return ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }

    private function articleSchema(): ?array
    {
        if (!$this->article) {
            return null;
        }
        return [
            '@type'            => 'NewsArticle',
            'headline'         => $this->title,
            'description'      => $this->description,
            'datePublished'    => $this->article['published'],
            'dateModified'     => $this->article['modified'],
            'articleSection'   => $this->article['section'],
            'image'            => $this->ogImage(),
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $this->canonical],
            'publisher'        => ['@id' => url('/') . '#organization'],
            'author'           => ['@type' => 'Organization', 'name' => $this->siteName()],
        ];
    }

    /** Renders the complete <head> meta block. */
    public function render(): string
    {
        $graph = [$this->organizationSchema()];
        if ($crumbs = $this->breadcrumbSchema()) {
            $graph[] = $crumbs;
        }
        if ($article = $this->articleSchema()) {
            $graph[] = $article;
        }

        $jsonLd = json_encode(
            ['@context' => 'https://schema.org', '@graph' => $graph],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        $out = [];
        $out[] = '<title>' . e($this->fullTitle()) . '</title>';
        $out[] = '<meta name="description" content="' . e($this->description) . '">';
        if ($this->canonical) {
            $out[] = '<link rel="canonical" href="' . e($this->canonical) . '">';
        }
        $out[] = '<meta name="robots" content="' . ($this->noindex ? 'noindex,nofollow' : 'index,follow,max-image-preview:large') . '">';

        // Open Graph
        $out[] = '<meta property="og:type" content="' . e($this->type) . '">';
        $out[] = '<meta property="og:site_name" content="' . e($this->siteName()) . '">';
        $out[] = '<meta property="og:title" content="' . e($this->fullTitle()) . '">';
        $out[] = '<meta property="og:description" content="' . e($this->description) . '">';
        $out[] = '<meta property="og:url" content="' . e($this->canonical ?: url('/')) . '">';
        $out[] = '<meta property="og:image" content="' . e($this->ogImage()) . '">';
        $out[] = '<meta property="og:locale" content="en_PK">';
        if ($this->article) {
            $out[] = '<meta property="article:published_time" content="' . e($this->article['published']) . '">';
            $out[] = '<meta property="article:modified_time" content="' . e($this->article['modified']) . '">';
            $out[] = '<meta property="article:section" content="' . e($this->article['section']) . '">';
        }

        // Twitter
        $out[] = '<meta name="twitter:card" content="summary_large_image">';
        $out[] = '<meta name="twitter:title" content="' . e($this->fullTitle()) . '">';
        $out[] = '<meta name="twitter:description" content="' . e($this->description) . '">';
        $out[] = '<meta name="twitter:image" content="' . e($this->ogImage()) . '">';

        // Geo
        $out[] = '<meta name="geo.region" content="PK-PB">';
        $out[] = '<meta name="geo.placename" content="Hafizabad, Punjab">';

        $out[] = '<script type="application/ld+json">' . $jsonLd . '</script>';

        return implode("\n", $out);
    }
}
