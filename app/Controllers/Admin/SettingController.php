<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\LogoService;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Setting;

final class SettingController extends AdminController
{
    /** Editable settings, grouped for the form. */
    public const GROUPS = [
        'Identity' => [
            'site_name'    => ['Site Name', 'text'],
            'site_name_ur' => ['Site Name (Urdu)', 'text'],
            'tagline'      => ['Tagline', 'text'],
        ],
        'Contact' => [
            'phone'    => ['Phone', 'text'],
            'whatsapp' => ['WhatsApp', 'text'],
            'email'    => ['Email', 'text'],
            'address'  => ['Address', 'text'],
            'map_embed'=> ['Google Maps Embed URL', 'text'],
        ],
        'Social' => [
            'facebook' => ['Facebook URL', 'text'],
            'youtube'  => ['YouTube URL', 'text'],
        ],
        'Homepage Statistics' => [
            'stat_students'  => ['Students', 'text'],
            'stat_faculty'   => ['Faculty Members', 'text'],
            'stat_pass_rate' => ['Board Pass Rate', 'text'],
            'stat_years'     => ['Years Serving', 'text'],
        ],
        'Mobile App' => [
            'app_play_store_url' => ['Google Play Store Link', 'text'],
            'app_app_store_url'  => ['Apple App Store Link', 'text'],
        ],
        'Admissions' => [
            'admissions_open' => ['Admissions Open (1 = yes, 0 = no)', 'text'],
            'admissions_note' => ['Admissions Note', 'textarea'],
        ],
        'Statements' => [
            'mission'      => ['Mission Statement', 'textarea'],
            'vision'       => ['Vision Statement', 'textarea'],
            'footer_about' => ['Footer About Text', 'textarea'],
        ],
    ];

    public function index(): void
    {
        $this->adminView('admin/settings/index', [
            'pageTitle' => 'Site Settings',
            'groups'    => self::GROUPS,
            'values'    => Setting::allAsMap(),
            'hasLogo'   => LogoService::hasCustom(),
        ]);
    }

    public function update(): void
    {
        $allowed = [];
        foreach (self::GROUPS as $fields) {
            foreach ($fields as $key => $def) {
                $allowed[] = $key;
            }
        }
        foreach ($allowed as $key) {
            if (isset($_POST[$key])) {
                Setting::put($key, trim((string) $_POST[$key]));
            }
        }

        // Logo is optional on this form — only processed when a file is attached
        $logoError = LogoService::store('logo');
        $logoChanged = $logoError === null && !empty($_FILES['logo']['name']);

        ActivityLog::record('updated', 'settings', $logoChanged ? 'Site settings and logo' : 'Site settings');

        if ($logoError !== null) {
            Session::flash('error', $logoError . ' Your other settings were saved.');
        } elseif ($logoChanged) {
            Session::flash('success', 'Settings saved and the new logo is live across the website, admin panel and favicon.');
        } else {
            Session::flash('success', 'Settings saved — the public website reflects them immediately.');
        }

        redirect('/admin/settings');
    }

    /** Delete the uploaded logo and fall back to the bundled Kohsar crest. */
    public function removeLogo(): void
    {
        if (!LogoService::hasCustom()) {
            Session::flash('error', 'There is no uploaded logo to remove.');
            redirect('/admin/settings');
        }

        LogoService::remove();
        ActivityLog::record('deleted', 'settings', 'Custom logo removed — reverted to the default crest');
        Session::flash('success', 'Custom logo removed. The site has reverted to the default PCS crest.');
        redirect('/admin/settings');
    }
}
