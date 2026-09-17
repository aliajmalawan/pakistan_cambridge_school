<?php

declare(strict_types=1);

/** @var App\Core\Router $router */

use App\Controllers\Admin;
use App\Controllers\AdmissionController;
use App\Controllers\BlogController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\PublicController;

// ---------- Public website ----------
$router->get('/', [HomeController::class, 'index']);

// ---------- Public: blog ----------
$router->get('/blogs', [BlogController::class, 'index']);
$router->get('/blogs/{slug}', [BlogController::class, 'show']);

// ---------- Public CMS pages ----------
$router->get('/page/{slug}', [PublicController::class, 'content']);
$router->get('/about', [PublicController::class, 'contentRoot']);
$router->get('/facilities', [PublicController::class, 'contentRoot']);
$router->get('/rules', [PublicController::class, 'contentRoot']);
// One-segment public routes are resolved against the system-page registry.
// This sits before admin routes only for non-/admin paths; /admin/... has its own exact routes below.
$router->get('/vision-mission', [PublicController::class, 'routePage']);
$router->get('/leadership', [PublicController::class, 'routePage']);
$router->get('/academics', [PublicController::class, 'routePage']);
$router->get('/academic-calendar', [PublicController::class, 'routePage']);
$router->get('/programs', [PublicController::class, 'routePage']);
$router->get('/fees', [PublicController::class, 'routePage']);
$router->get('/faculty', [PublicController::class, 'routePage']);
$router->get('/news', [PublicController::class, 'routePage']);
$router->get('/gallery', [PublicController::class, 'routePage']);
$router->get('/admissions', [PublicController::class, 'routePage']);
$router->post('/admissions/apply', [AdmissionController::class, 'store']);
$router->get('/downloads', [PublicController::class, 'routePage']);
$router->get('/downloads/{id}', [PublicController::class, 'download']);
$router->get('/contact', [PublicController::class, 'routePage']);
$router->post('/contact/send', [ContactController::class, 'store']);
$router->get('/search', [PublicController::class, 'routePage']);
$router->get('/sitemap.xml', [PublicController::class, 'sitemap']);


// ---------- Admin: auth ----------
$router->get('/admin/login', [Admin\AuthController::class, 'showLogin']);
$router->post('/admin/login', [Admin\AuthController::class, 'login']);
$router->post('/admin/logout', [Admin\AuthController::class, 'logout']);

// ---------- Admin: dashboard ----------
$router->get('/admin', [Admin\DashboardController::class, 'index']);
$router->post('/admin/dashboard/prefs', [Admin\DashboardController::class, 'savePrefs']);

// ---------- Admin: analytics ----------
$router->get('/admin/analytics', [Admin\AnalyticsController::class, 'index']);

// ---------- Admin: sliders ----------
$router->get('/admin/sliders', [Admin\SliderController::class, 'index']);
$router->get('/admin/sliders/create', [Admin\SliderController::class, 'create']);
$router->post('/admin/sliders/store', [Admin\SliderController::class, 'store']);
$router->get('/admin/sliders/edit/{id}', [Admin\SliderController::class, 'edit']);
$router->post('/admin/sliders/update/{id}', [Admin\SliderController::class, 'update']);
$router->post('/admin/sliders/delete/{id}', [Admin\SliderController::class, 'destroy']);

// ---------- Admin: menu builder ----------
$router->get('/admin/menu', [Admin\MenuController::class, 'index']);
$router->post('/admin/menu/menus/create', [Admin\MenuController::class, 'createMenu']);
$router->post('/admin/menu/menus/rename/{id}', [Admin\MenuController::class, 'renameMenu']);
$router->post('/admin/menu/menus/delete/{id}', [Admin\MenuController::class, 'deleteMenu']);
$router->post('/admin/menu/{id}/structure', [Admin\MenuController::class, 'saveStructure']);
$router->post('/admin/menu/{id}/item', [Admin\MenuController::class, 'saveItem']);
$router->post('/admin/menu/item/delete/{id}', [Admin\MenuController::class, 'deleteItem']);

// ---------- Admin: core values ----------
$router->get('/admin/values', [Admin\CoreValueController::class, 'index']);
$router->get('/admin/values/create', [Admin\CoreValueController::class, 'create']);
$router->post('/admin/values/store', [Admin\CoreValueController::class, 'store']);
$router->get('/admin/values/edit/{id}', [Admin\CoreValueController::class, 'edit']);
$router->post('/admin/values/update/{id}', [Admin\CoreValueController::class, 'update']);
$router->post('/admin/values/delete/{id}', [Admin\CoreValueController::class, 'destroy']);

// ---------- Admin: pages ----------
$router->get('/admin/pages', [Admin\PageController::class, 'index']);
$router->get('/admin/pages/create', [Admin\PageController::class, 'create']);
$router->post('/admin/pages/store', [Admin\PageController::class, 'store']);
$router->get('/admin/pages/edit/{id}', [Admin\PageController::class, 'edit']);
$router->post('/admin/pages/update/{id}', [Admin\PageController::class, 'update']);
$router->post('/admin/pages/delete/{id}', [Admin\PageController::class, 'destroy']);

// ---------- Admin: news ----------
$router->get('/admin/news', [Admin\NewsController::class, 'index']);
$router->get('/admin/news/create', [Admin\NewsController::class, 'create']);
$router->post('/admin/news/store', [Admin\NewsController::class, 'store']);
$router->get('/admin/news/edit/{id}', [Admin\NewsController::class, 'edit']);
$router->post('/admin/news/update/{id}', [Admin\NewsController::class, 'update']);
$router->post('/admin/news/delete/{id}', [Admin\NewsController::class, 'destroy']);

// ---------- Admin: facilities ----------
$router->get('/admin/facilities', [Admin\FacilityController::class, 'index']);
$router->get('/admin/facilities/{kind}/create', [Admin\FacilityController::class, 'create']);
$router->post('/admin/facilities/{kind}/store', [Admin\FacilityController::class, 'store']);
$router->get('/admin/facilities/{kind}/edit/{id}', [Admin\FacilityController::class, 'edit']);
$router->post('/admin/facilities/{kind}/update/{id}', [Admin\FacilityController::class, 'update']);
$router->post('/admin/facilities/{kind}/delete/{id}', [Admin\FacilityController::class, 'destroy']);

// ---------- Admin: blogs ----------
$router->get('/admin/blogs', [Admin\BlogController::class, 'index']);
$router->get('/admin/blogs/create', [Admin\BlogController::class, 'create']);
$router->post('/admin/blogs/store', [Admin\BlogController::class, 'store']);
$router->get('/admin/blogs/edit/{id}', [Admin\BlogController::class, 'edit']);
$router->post('/admin/blogs/update/{id}', [Admin\BlogController::class, 'update']);
$router->post('/admin/blogs/delete/{id}', [Admin\BlogController::class, 'destroy']);

// ---------- Admin: programs ----------
$router->get('/admin/programs', [Admin\ProgramController::class, 'index']);
$router->get('/admin/programs/create', [Admin\ProgramController::class, 'create']);
$router->post('/admin/programs/store', [Admin\ProgramController::class, 'store']);
$router->get('/admin/programs/edit/{id}', [Admin\ProgramController::class, 'edit']);
$router->post('/admin/programs/update/{id}', [Admin\ProgramController::class, 'update']);
$router->post('/admin/programs/delete/{id}', [Admin\ProgramController::class, 'destroy']);

// ---------- Admin: faculty ----------
$router->get('/admin/faculty', [Admin\FacultyController::class, 'index']);
$router->get('/admin/faculty/create', [Admin\FacultyController::class, 'create']);
$router->post('/admin/faculty/store', [Admin\FacultyController::class, 'store']);
$router->get('/admin/faculty/edit/{id}', [Admin\FacultyController::class, 'edit']);
$router->post('/admin/faculty/update/{id}', [Admin\FacultyController::class, 'update']);
$router->post('/admin/faculty/delete/{id}', [Admin\FacultyController::class, 'destroy']);

$router->get('/admin/downloads', [Admin\DownloadController::class, 'index']);
$router->get('/admin/downloads/create', [Admin\DownloadController::class, 'create']);
$router->post('/admin/downloads/store', [Admin\DownloadController::class, 'store']);
$router->get('/admin/downloads/edit/{id}', [Admin\DownloadController::class, 'edit']);
$router->post('/admin/downloads/update/{id}', [Admin\DownloadController::class, 'update']);
$router->post('/admin/downloads/delete/{id}', [Admin\DownloadController::class, 'destroy']);

// ---------- Admin: leadership ----------
$router->get('/admin/leadership', [Admin\LeadershipController::class, 'index']);
$router->get('/admin/leadership/create', [Admin\LeadershipController::class, 'create']);
$router->post('/admin/leadership/store', [Admin\LeadershipController::class, 'store']);
$router->get('/admin/leadership/edit/{id}', [Admin\LeadershipController::class, 'edit']);
$router->post('/admin/leadership/update/{id}', [Admin\LeadershipController::class, 'update']);
$router->post('/admin/leadership/delete/{id}', [Admin\LeadershipController::class, 'destroy']);

// ---------- Admin: fee structure ----------
$router->get('/admin/fees', [Admin\FeeController::class, 'index']);
$router->get('/admin/fees/{kind}/create', [Admin\FeeController::class, 'create']);
$router->post('/admin/fees/{kind}/store', [Admin\FeeController::class, 'store']);
$router->get('/admin/fees/{kind}/edit/{id}', [Admin\FeeController::class, 'edit']);
$router->post('/admin/fees/{kind}/update/{id}', [Admin\FeeController::class, 'update']);
$router->post('/admin/fees/{kind}/delete/{id}', [Admin\FeeController::class, 'destroy']);

// ---------- Admin: academics (framework / calendar / grading) ----------
$router->get('/admin/academics', [Admin\AcademicController::class, 'index']);
$router->get('/admin/academics/{kind}/create', [Admin\AcademicController::class, 'create']);
$router->post('/admin/academics/{kind}/store', [Admin\AcademicController::class, 'store']);
$router->get('/admin/academics/{kind}/edit/{id}', [Admin\AcademicController::class, 'edit']);
$router->post('/admin/academics/{kind}/update/{id}', [Admin\AcademicController::class, 'update']);
$router->post('/admin/academics/{kind}/delete/{id}', [Admin\AcademicController::class, 'destroy']);

// ---------- Admin: academic calendar (terms / dated events) ----------
$router->get('/admin/calendar', [Admin\CalendarController::class, 'index']);
$router->get('/admin/calendar/{kind}/create', [Admin\CalendarController::class, 'create']);
$router->post('/admin/calendar/{kind}/store', [Admin\CalendarController::class, 'store']);
$router->get('/admin/calendar/{kind}/edit/{id}', [Admin\CalendarController::class, 'edit']);
$router->post('/admin/calendar/{kind}/update/{id}', [Admin\CalendarController::class, 'update']);
$router->post('/admin/calendar/{kind}/delete/{id}', [Admin\CalendarController::class, 'destroy']);

// ---------- Admin: about page (journey milestones) ----------
$router->get('/admin/about', [Admin\AboutController::class, 'index']);
$router->get('/admin/about/{kind}/create', [Admin\AboutController::class, 'create']);
$router->post('/admin/about/{kind}/store', [Admin\AboutController::class, 'store']);
$router->get('/admin/about/{kind}/edit/{id}', [Admin\AboutController::class, 'edit']);
$router->post('/admin/about/{kind}/update/{id}', [Admin\AboutController::class, 'update']);
$router->post('/admin/about/{kind}/delete/{id}', [Admin\AboutController::class, 'destroy']);

// ---------- Admin: testimonials ----------
$router->get('/admin/testimonials', [Admin\TestimonialController::class, 'index']);
$router->get('/admin/testimonials/create', [Admin\TestimonialController::class, 'create']);
$router->post('/admin/testimonials/store', [Admin\TestimonialController::class, 'store']);
$router->get('/admin/testimonials/edit/{id}', [Admin\TestimonialController::class, 'edit']);
$router->post('/admin/testimonials/update/{id}', [Admin\TestimonialController::class, 'update']);
$router->post('/admin/testimonials/delete/{id}', [Admin\TestimonialController::class, 'destroy']);

// ---------- Admin: gallery ----------
$router->get('/admin/gallery', [Admin\GalleryController::class, 'index']);
$router->get('/admin/gallery/create', [Admin\GalleryController::class, 'create']);
$router->post('/admin/gallery/store', [Admin\GalleryController::class, 'store']);
$router->get('/admin/gallery/edit/{id}', [Admin\GalleryController::class, 'edit']);
$router->post('/admin/gallery/update/{id}', [Admin\GalleryController::class, 'update']);
$router->post('/admin/gallery/delete/{id}', [Admin\GalleryController::class, 'destroy']);
$router->post('/admin/gallery/{id}/images', [Admin\GalleryController::class, 'addImage']);
$router->post('/admin/gallery/images/delete/{id}', [Admin\GalleryController::class, 'deleteImage']);

// ---------- Admin: admissions ----------
$router->get('/admin/admissions', [Admin\AdmissionController::class, 'index']);
$router->get('/admin/admissions/show/{id}', [Admin\AdmissionController::class, 'show']);
$router->get('/admin/admissions/edit/{id}', [Admin\AdmissionController::class, 'edit']);
$router->post('/admin/admissions/update/{id}', [Admin\AdmissionController::class, 'update']);
$router->post('/admin/admissions/status/{id}', [Admin\AdmissionController::class, 'updateStatus']);
$router->post('/admin/admissions/delete/{id}', [Admin\AdmissionController::class, 'destroy']);

// ---------- Admin: messages ----------
$router->get('/admin/messages', [Admin\MessageController::class, 'index']);
$router->get('/admin/messages/show/{id}', [Admin\MessageController::class, 'show']);
$router->post('/admin/messages/delete/{id}', [Admin\MessageController::class, 'destroy']);

// ---------- Admin: settings ----------
$router->get('/admin/settings', [Admin\SettingController::class, 'index']);
$router->post('/admin/settings', [Admin\SettingController::class, 'update']);
$router->post('/admin/settings/logo/remove', [Admin\SettingController::class, 'removeLogo']);

// ---------- Admin: users ----------
$router->get('/admin/users', [Admin\UserController::class, 'index']);
$router->get('/admin/users/create', [Admin\UserController::class, 'create']);
$router->post('/admin/users/store', [Admin\UserController::class, 'store']);
$router->get('/admin/users/edit/{id}', [Admin\UserController::class, 'edit']);
$router->post('/admin/users/update/{id}', [Admin\UserController::class, 'update']);
$router->post('/admin/users/delete/{id}', [Admin\UserController::class, 'destroy']);
