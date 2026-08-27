-- ============================================================
-- Kohsar — Traffic analytics for the admin dashboard
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 006_analytics.sql
-- ============================================================

USE kohsarschool;

CREATE TABLE IF NOT EXISTS page_views (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(255) NOT NULL,
    -- Visitors are counted by a salted hash, never a stored IP address:
    -- enough to count unique people, not enough to identify one.
    visitor_hash CHAR(64) NOT NULL,
    referrer VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_views_created (created_at),
    INDEX idx_views_path (path),
    INDEX idx_views_visitor (visitor_hash)
) ENGINE=InnoDB;
