<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\CoreValue;

final class CoreValueController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/values/index', [
            'pageTitle' => 'Core Values',
            'values'    => CoreValue::all('sort_order ASC, id ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/values/form', ['pageTitle' => 'Add Core Value', 'value' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/values/create');
        }
        CoreValue::create($data);
        clear_old();
        ActivityLog::record('created', 'page', 'Core value "' . $data['title'] . '"');
        Session::flash('success', 'Core value added.');
        redirect('/admin/values');
    }

    public function edit(string $id): void
    {
        $value = CoreValue::find((int) $id);
        if (!$value) {
            Session::flash('error', 'Core value not found.');
            redirect('/admin/values');
        }
        $this->adminView('admin/values/form', ['pageTitle' => 'Edit Core Value', 'value' => $value]);
    }

    public function update(string $id): void
    {
        if (!CoreValue::find((int) $id)) {
            redirect('/admin/values');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/values/edit/' . $id);
        }
        CoreValue::update((int) $id, $data);
        clear_old();
        ActivityLog::record('updated', 'page', 'Core value "' . $data['title'] . '"');
        Session::flash('success', 'Core value updated.');
        redirect('/admin/values');
    }

    public function destroy(string $id): void
    {
        $value = CoreValue::find((int) $id);
        if ($value) {
            CoreValue::delete((int) $id);
            ActivityLog::record('deleted', 'page', 'Core value "' . $value['title'] . '"');
            Session::flash('success', 'Core value removed.');
        }
        redirect('/admin/values');
    }

    private function validated(): ?array
    {
        $icon = $this->input('icon');
        $accent = $this->input('accent');

        $data = [
            'title'       => $this->input('title'),
            'description' => $this->input('description'),
            'icon'        => isset(CoreValue::ICONS[$icon]) ? $icon : 'shield',
            'accent'      => isset(CoreValue::ACCENTS[$accent]) ? $accent : 'accent-blue',
            'sort_order'  => (int) $this->input('sort_order', '0'),
            'status'      => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];

        if (mb_strlen($data['title']) < 2 || mb_strlen($data['description']) < 15) {
            keep_old($data);
            Session::flash('error', 'A value needs a title and a description of at least 15 characters.');
            return null;
        }
        return $data;
    }
}
