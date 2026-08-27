<?php $posts = $paged['items']; ?>

<section class="inner-hero">
    <div class="inner-hero-shape inner-hero-shape-a"></div>
    <div class="inner-hero-shape inner-hero-shape-b"></div>
    <div class="container inner-hero-grid">
        <div>
            <span class="eyebrow light"><i></i>Pakistan Cambridge School • Hafizabad</span>
            <h1>Blog</h1>
            <p>Stories, updates and notes from around the campus.</p>
        </div>
    </div>
</section>

<div class="page-breadcrumb">
    <div class="container"><a href="<?= url('/') ?>">Home</a><span>›</span><strong>Blog</strong></div>
</div>

<section class="section-pad inner-page">
    <div class="container">
        <div class="news-grid inner-news-grid">
            <?php foreach ($posts as $item): ?>
                <a class="news-card" href="<?= url('/blogs/' . $item['slug']) ?>">
                    <?php if (!empty($item['image'])): ?><img src="<?= e(upload_url($item['image'])) ?>" alt="<?= e($item['title']) ?>"><?php else: ?><div class="news-placeholder"><span><?= e(strtoupper(substr($item['author'] ?: 'B', 0, 1))) ?></span></div><?php endif; ?>
                    <div class="news-body">
                        <span class="news-meta">By <?= e($item['author']) ?> • <?= e(format_date($item['published_at'], 'd M Y')) ?></span>
                        <h3><?= e($item['title']) ?></h3>
                        <p><?= e($item['excerpt'] ?: excerpt($item['content'], 115)) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if (!$posts): ?><div class="empty-state">No blog posts have been published yet.</div><?php endif; ?>
        </div>

        <?php if ($paged['pages'] > 1): ?>
        <div class="blog-pagination">
            <?php if ($paged['page'] > 1): ?><a class="btn btn-ghost" href="<?= url('/blogs') ?>?page=<?= $paged['page'] - 1 ?>">← Newer posts</a><?php else: ?><span></span><?php endif; ?>
            <span class="blog-pagination-status">Page <?= (int) $paged['page'] ?> of <?= (int) $paged['pages'] ?></span>
            <?php if ($paged['page'] < $paged['pages']): ?><a class="btn btn-ghost" href="<?= url('/blogs') ?>?page=<?= $paged['page'] + 1 ?>">Older posts →</a><?php else: ?><span></span><?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="inner-cta"><div class="container"><div><span class="eyebrow light"><i></i>Pakistan Cambridge School</span><h2>Learning today. <em>Leading tomorrow.</em></h2></div><a class="btn btn-white" href="<?= url('/admissions') ?>">Start an application →</a></div></section>
