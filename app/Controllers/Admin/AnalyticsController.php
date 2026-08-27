<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Session;
use App\Models\DashboardStats;
use App\Models\PageView;

final class AnalyticsController extends AdminController
{
    /** Reporting windows offered in the toolbar. */
    public const RANGES = [7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 90 days'];

    public function __construct()
    {
        parent::__construct();
        if (!in_array(Auth::user()['role'] ?? 'editor', ['admin', 'superadmin'], true)) {
            Session::flash('error', 'Analytics is available to administrators.');
            redirect('/admin');
        }
    }

    public function index(): void
    {
        $days = (int) $this->queryParam('range', '30');
        if (!isset(self::RANGES[$days])) {
            $days = 30;
        }

        // Same-length preceding window, for the ± comparisons
        $isSuper = (Auth::user()['role'] ?? '') === 'superadmin';

        $this->adminView('admin/analytics/index', [
            'pageTitle'   => 'Analytics',
            'days'        => $days,
            'ranges'      => self::RANGES,
            'isSuper'     => $isSuper,

            // Headline figures for the period
            'totalViews'      => PageView::totalViews($days),
            'uniqueVisitors'  => PageView::uniqueVisitors($days),
            'pagesPerVisitor' => PageView::pagesPerVisitor($days),
            'directShare'     => PageView::directShare($days),
            'onlineNow'       => PageView::onlineNow(),
            'viewsToday'      => PageView::viewsOn(date('Y-m-d')),
            'viewsYesterday'  => PageView::viewsOn(date('Y-m-d', strtotime('-1 day'))),

            // Charts
            'dailyViews'   => PageView::dailySeries($days, false),
            'dailyUnique'  => PageView::dailySeries($days, true),
            'topPages'     => PageView::topPages($days, 10),
            'topReferrers' => PageView::topReferrers($days, 8),
            'byWeekday'    => PageView::byWeekday($days),

            // Content and enquiry trends
            'admissionsMonthly' => DashboardStats::monthlySeries('admissions'),
            'messagesMonthly'   => DashboardStats::monthlySeries('contact_messages'),
            'contentMonthly'    => DashboardStats::contentPerMonth(),
            'statusSplit'       => DashboardStats::admissionStatusSplit(),
            'classDemand'       => DashboardStats::classDemandBars(8),

            'loginsDaily'   => $isSuper ? DashboardStats::loginsDaily(14) : [],
            'usersByRole'   => $isSuper ? DashboardStats::usersByRole() : [],

            'trackingSince' => PageView::firstRecordedAt(),
        ]);
    }
}
