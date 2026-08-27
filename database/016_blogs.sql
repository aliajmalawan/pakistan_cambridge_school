-- ============================================================
-- Pakistan Cambridge School — Blogs
-- A separate content type from News & Notices: longer-form,
-- author-bylined posts with their own list and article pages.
-- Import: mysql -u root --default-character-set=utf8mb4 pakistan_cambridge_school < 016_blogs.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS blogs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(200) NOT NULL UNIQUE,
    title VARCHAR(220) NOT NULL,
    author VARCHAR(120) NOT NULL DEFAULT '',
    excerpt VARCHAR(300) NOT NULL DEFAULT '',
    content MEDIUMTEXT NOT NULL,
    image VARCHAR(255) NULL,
    published_at DATETIME NOT NULL,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_blogs_published (status, published_at)
) ENGINE=InnoDB;

-- Placeholder post so the list/article pages render immediately after
-- install — replace it via Admin → Blogs.
INSERT INTO blogs (slug, title, author, excerpt, content, published_at) VALUES
('welcome-to-the-pcs-blog', 'Welcome to the PCS Blog', 'Pakistan Cambridge School',
 'This is a placeholder post — replace it with a real article via Admin → Blogs.',
 '<p>This is a placeholder post. Replace it with a real article — classroom stories, student achievements, teaching notes, campus updates — via <strong>Admin → Blogs</strong>.</p>',
 NOW());
