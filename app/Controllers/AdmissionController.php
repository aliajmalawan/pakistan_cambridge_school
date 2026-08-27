<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Admission;
use App\Models\ActivityLog;

final class AdmissionController extends Controller
{
    public function store(): void
    {
        Csrf::verify();

        $data = [];
        foreach (Admission::EDITABLE as $field) {
            $data[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $errors = Admission::validate($data);
        if ($errors) {
            keep_old($data);
            Session::flash('error', implode(' ', $errors));
            redirect('/admissions#apply');
        }

        $data['bform'] = Admission::normaliseBform($data['bform']);
        $data['app_no'] = Admission::pendingAppNo();
        $data['status'] = 'pending';

        $id = Admission::create($data);
        $appNo = Admission::formatAppNo($id);
        Admission::update($id, ['app_no' => $appNo]);

        ActivityLog::record('submitted', 'admission', $appNo . ' — ' . $data['student_name'], $data['student_name']);
        clear_old();

        Session::flash('success', 'Application submitted — your reference number is ' . $appNo . '. Our admissions office will contact you on ' . $data['guardian_phone'] . '.');
        redirect('/admissions#apply');
    }
}
