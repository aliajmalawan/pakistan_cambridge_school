<section class="inner-hero">
    <div class="inner-hero-shape inner-hero-shape-a"></div>
    <div class="inner-hero-shape inner-hero-shape-b"></div>
    <div class="container inner-hero-grid">
        <div>
            <span class="eyebrow light"><i></i>Pakistan Cambridge School Blog</span>
            <h1><?= e($post['title']) ?></h1>
            <p>By <?= e($post['author']) ?> • <?= e(format_date($post['published_at'], 'd F Y')) ?></p>
        </div>
    </div>
</section>

<div class="page-breadcrumb">
    <div class="container"><a href="<?= url('/') ?>">Home</a><span>›</span><a href="<?= url('/blogs') ?>">Blog</a><span>›</span><strong><?= e($post['title']) ?></strong></div>
</div>

<section class="section-pad inner-page">
    <div class="container">
        <div class="intro-copy">
            <?php if (!empty($post['image'])): ?><img src="<?= e(upload_url($post['image'])) ?>" alt="<?= e($post['title']) ?>" class="blog-post-image"><?php endif; ?>
            <div class="rich-content"><?= $post['content'] ?></div>
            <a class="text-link" href="<?= url('/blogs') ?>">← Back to all posts</a>
        </div>

        <?php if ($related): ?>
        <div class="section-row inner-section-row" style="margin-top:70px">
            <div><span class="eyebrow dark"><i></i>More from the blog</span><h2>Keep <em>reading.</em></h2></div>
        </div>
        <div class="news-grid inner-news-grid">
            <?php foreach ($related as $item): ?>
                <a class="news-card" href="<?= url('/blogs/' . $item['slug']) ?>">
                    <?php if (!empty($item['image'])): ?><img src="<?= e(upload_url($item['image'])) ?>" alt="<?= e($item['title']) ?>"><?php else: ?><div class="news-placeholder"><span><?= e(strtoupper(substr($item['author'] ?: 'B', 0, 1))) ?></span></div><?php endif; ?>
                    <div class="news-body">
                        <span class="news-meta">By <?= e($item['author']) ?> • <?= e(format_date($item['published_at'], 'd M Y')) ?></span>
                        <h3><?= e($item['title']) ?></h3>
                        <p><?= e($item['excerpt'] ?: excerpt($item['content'], 115)) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="inner-cta"><div class="container"><div><span class="eyebrow light"><i></i>Pakistan Cambridge School</span><h2>Learning today. <em>Leading tomorrow.</em></h2></div><a class="btn btn-white" href="<?= url('/admissions') ?>">Start an application →</a></div></section>
