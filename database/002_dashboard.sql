-- ============================================================
-- Kohsar — Admin Dashboard upgrade
-- Activity log + per-user dashboard widget preferences
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 002_dashboard.sql
-- ============================================================

USE kohsarschool;

CREATE TABLE IF NOT EXISTS activity_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,               -- NULL = public visitor action
    actor VARCHAR(120) NOT NULL,             -- display name at time of action
    action VARCHAR(40) NOT NULL,             -- e.g. login, created, updated, deleted, submitted, status_changed
    subject_type VARCHAR(40) NOT NULL,       -- e.g. news, page, admission, message, settings, slider
    subject_label VARCHAR(220) NOT NULL,     -- human-readable target
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_created (created_at),
    INDEX idx_activity_subject (subject_type)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS dashboard_prefs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    widgets VARCHAR(500) NOT NULL,           -- comma-separated visible widget keys, in order
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_prefs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed the log with the system's own birth so the feed is never empty
INSERT INTO activity_log (user_id, actor, action, subject_type, subject_label) VALUES
(1, 'Site Administrator', 'created', 'system', 'Kohsar CMS installed and database seeded');
