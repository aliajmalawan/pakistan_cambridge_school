<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Admission;

final class AdmissionController extends AdminController
{
    public function index(): void
    {
        $status = $this->queryParam('status');
        $search = $this->queryParam('q');
        $condition = '1';
        $params = [];
        if (isset(Admission::STATUSES[$status])) {
            $condition .= ' AND status = ?';
            $params[] = $status;
        } else {
            $status = '';
        }
        if ($search !== '') {
            $condition .= ' AND (student_name LIKE ? OR father_name LIKE ? OR app_no LIKE ? OR guardian_phone LIKE ?)';
            $like = '%' . $search . '%';
            array_push($params, $like, $like, $like, $like);
        }
        $this->adminView('admin/admissions/index', [
            'pageTitle' => 'Admission Applications',
            'status'    => $status,
            'search'    => $search,
            'paged'     => Admission::paginate($this->pageParam(), ADMIN_PER_PAGE, $condition, $params, 'created_at DESC'),
        ]);
    }

    public function show(string $id): void
    {
        $application = Admission::find((int) $id);
        if (!$application) {
            Session::flash('error', 'Application not found.');
            redirect('/admin/admissions');
        }
        $this->adminView('admin/admissions/show', [
            'pageTitle'   => 'Application ' . $application['app_no'],
            'application' => $application,
        ]);
    }

    public function edit(string $id): void
    {
        $application = Admission::find((int) $id);
        if (!$application) {
            Session::flash('error', 'Application not found.');
            redirect('/admin/admissions');
        }
        $this->adminView('admin/admissions/form', [
            'pageTitle'   => 'Edit ' . $application['app_no'],
            'application' => $application,
            'classes'     => Admission::CLASSES,
        ]);
    }

    public function update(string $id): void
    {
        $application = Admission::find((int) $id);
        if (!$application) {
            Session::flash('error', 'Application not found.');
            redirect('/admin/admissions');
        }

        $data = [];
        foreach (Admission::EDITABLE as $field) {
            $data[$field] = $this->input($field);
        }

        // The same rules the public form enforces, so a correction typed in the
        // office can never store something an applicant could not have sent.
        $errors = Admission::validate($data);
        if ($errors) {
            keep_old($data);
            Session::flash('error', implode(' ', $errors));
            redirect('/admin/admissions/edit/' . $id);
        }

        $data['bform'] = Admission::normaliseBform($data['bform']);

        // Log what actually moved — an application is a submitted record, so a
        // later edit by the office should be traceable.
        $changed = array_keys(array_filter(
            $data,
            fn($value, $field) => (string) $value !== (string) ($application[$field] ?? ''),
            ARRAY_FILTER_USE_BOTH
        ));

        Admission::update((int) $id, $data);
        clear_old();

        if ($changed) {
            ActivityLog::record(
                'edited',
                'admission',
                $application['app_no'] . ' — ' . implode(', ', $changed)
            );
        }

        Session::flash('success', $changed
            ? 'Application ' . $application['app_no'] . ' updated.'
            : 'No changes to save.');
        redirect('/admin/admissions/show/' . $id);
    }

    public function updateStatus(string $id): void
    {
        $application = Admission::find((int) $id);
        $status = $this->input('status');
        if ($application && isset(Admission::STATUSES[$status])) {
            Admission::update((int) $id, ['status' => $status]);
            ActivityLog::record('status_changed', 'admission', $application['app_no'] . ' → ' . Admission::STATUSES[$status]);
            Session::flash('success', 'Application ' . $application['app_no'] . ' marked as ' . Admission::STATUSES[$status] . '.');
        }
        redirect('/admin/admissions/show/' . $id);
    }

    public function destroy(string $id): void
    {
        if (Admission::find((int) $id)) {
            Admission::delete((int) $id);
            Session::flash('success', 'Application deleted.');
        }
        redirect('/admin/admissions');
    }
}
