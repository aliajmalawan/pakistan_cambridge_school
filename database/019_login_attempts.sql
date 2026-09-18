-- ============================================================
-- Pakistan Cambridge School — Admin login attempt log
-- Powers brute-force lockout on /admin/login (see Auth::attempt
-- and App\Models\LoginAttempt). IP is stored as a one-way hash,
-- never in the clear — same privacy approach as page_views.
-- Import: mysql -u root --default-character-set=utf8mb4 pakistan_cambridge_school < 019_login_attempts.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS login_attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(160) NOT NULL,
    ip_hash CHAR(64) NOT NULL,
    success TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_time (email, created_at),
    INDEX idx_ip_time (ip_hash, created_at)
) ENGINE=InnoDB;
