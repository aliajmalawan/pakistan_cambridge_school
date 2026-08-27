# Pakistan Cambridge School — Complete Website + CMS

This package now includes the professional responsive public website **and** the CMS/admin dashboard.

## Public website
- Professional white contact topbar + dark navy navigation inspired by the supplied Kohsar reference layout
- PCS logo and blue/green/gold brand system
- Responsive desktop/tablet/mobile navigation
- Dropdown navigation for About, Academics and Campus & Facilities
- Separate public pages for all CMS/system pages
- CMS content pages are linked through `/page/<slug>`
- Published pages created in Admin → Pages are automatically appended under **More** in the navbar until placed in Menu Builder
- News, programs, faculty, gallery, fees, academic calendar, downloads, admissions, contact and search pages

## Admin
- `/admin/login`
- Admin dashboard, Pages, Menu Builder, Sliders, Programs, Faculty, News, Gallery, Admissions, Settings, etc.

## Database
For a fresh XAMPP installation, the easiest option is to import **`database/PCS_INSTALL.sql`** once in phpMyAdmin. It creates `pakistan_cambridge_school` and applies all dashboard/frontend migrations plus PCS branding and navigation.

If the database is already installed, import only:

`database/015_pcs_pages_navigation.sql`

This converts the published CMS copy to Pakistan Cambridge School branding and builds the header navigation.

## Admin credentials
- Email: `admin@pcshfd.com`
- Password: `admin@2026`

If needed, run from `C:\xampp\htdocs\PCS`:

`php create_admin.php`

Then delete `create_admin.php` after successful setup.

## URLs
- Website: `http://localhost/PCS/`
- Admin: `http://localhost/PCS/admin/login`
- Search: `http://localhost/PCS/search`
