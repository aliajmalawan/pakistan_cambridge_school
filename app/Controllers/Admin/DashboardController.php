<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Admission;
use App\Models\ContactMessage;
use App\Models\DashboardPref;
use App\Models\DashboardStats;
use App\Models\Faculty;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\PageView;

/**
 * The operational overview: what needs attention today.
 * Deep traffic and trend reporting lives in AnalyticsController.
 */
final class DashboardController extends AdminController
{
    public function index(): void
    {
        $role = Auth::user()['role'] ?? 'editor';
        $isAdmin = in_array($role, ['admin', 'superadmin'], true);

        $split = DashboardStats::admissionStatusSplit();
        $pending = $split['pending'] ?? 0;
        $underReview = $split['under_review'] ?? 0;
        $unread = ContactMessage::unreadCount();
        $draftNews = News::count('status = ?', ['draft']);

        // "Needs attention" — only things an admin can actually act on
        $attention = [];
        if ($pending > 0) {
            $attention[] = [
                'label'  => $pending . ' application' . ($pending === 1 ? '' : 's') . ' awaiting review',
                'detail' => 'Applications sit as Pending until you move them through the pipeline.',
                'action' => 'Review applications',
                'href'   => '/admin/admissions?status=pending',
                'tone'   => 'warning',
            ];
        }
        if ($unread > 0) {
            $attention[] = [
                'label'  => $unread . ' unread message' . ($unread === 1 ? '' : 's'),
                'detail' => 'Families expect a reply within two working days.',
                'action' => 'Open inbox',
                'href'   => '/admin/messages',
                'tone'   => 'info',
            ];
        }
        if ($underReview > 0) {
            $attention[] = [
                'label'  => $underReview . ' application' . ($underReview === 1 ? '' : 's') . ' under review',
                'detail' => 'Assessment or interview stage — decide to accept or reject.',
                'action' => 'Continue review',
                'href'   => '/admin/admissions?status=under_review',
                'tone'   => 'info',
            ];
        }
        if ($draftNews > 0) {
            $attention[] = [
                'label'  => $draftNews . ' unpublished draft' . ($draftNews === 1 ? '' : 's'),
                'detail' => 'Drafts are invisible to visitors until published.',
                'action' => 'Open news',
                'href'   => '/admin/news',
                'tone'   => 'info',
            ];
        }

        $this->adminView('admin/dashboard/index', [
            'pageTitle'  => 'Dashboard',
            'widgets'    => DashboardPref::forUser((int) Auth::id()),
            'allWidgets' => DashboardPref::WIDGETS,
            'isAdmin'    => $isAdmin,
            'attention'  => $attention,

            // KPI row
            'pendingApps'     => $pending,
            'totalAdmissions' => $split['total'] ?? 0,
            'appsThisMonth'   => DashboardStats::thisMonth('admissions'),
            'appsLastMonth'   => DashboardStats::lastMonth('admissions'),
            'unreadMessages'  => $unread,
            'totalMessages'   => ContactMessage::count(),
            'viewsToday'      => $isAdmin ? PageView::viewsOn(date('Y-m-d')) : 0,
            'viewsYesterday'  => $isAdmin ? PageView::viewsOn(date('Y-m-d', strtotime('-1 day'))) : 0,
            'onlineNow'       => $isAdmin ? PageView::onlineNow() : 0,

            // Compact 7-day trend
            'weekViews'   => $isAdmin ? PageView::dailySeries(7, false) : [],
            'weekUnique'  => $isAdmin ? PageView::dailySeries(7, true) : [],

            // Content summary
            'publishedNews' => News::count('status = ?', ['published']),
            'draftNews'     => $draftNews,
            'facultyCount'  => Faculty::count('status = ?', ['published']),
            'galleryCount'  => GalleryImage::count(),

            // Lists
            'recentAdmissions' => Admission::where('1', [], 'created_at DESC', 5),
            'recentMessages'   => ContactMessage::where('1', [], 'created_at DESC', 5),
            'activity'         => ActivityLog::recent(8),
        ]);
    }

    /** Save the customize-widgets form. */
    public function savePrefs(): void
    {
        $keys = $_POST['widgets'] ?? [];
        DashboardPref::save((int) Auth::id(), is_array($keys) ? $keys : []);
        Session::flash('success', 'Dashboard layout saved.');
        redirect('/admin');
    }
}
