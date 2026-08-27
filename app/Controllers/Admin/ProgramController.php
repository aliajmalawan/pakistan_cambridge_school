<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\Program;

final class ProgramController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/programs/index', [
            'pageTitle' => 'Academic Programs',
            'programs'  => Program::all('sort_order ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/programs/form', ['pageTitle' => 'Add Program', 'program' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/programs/create');
        }
        if (Program::findBy('slug', $data['slug'])) {
            $data['slug'] .= '-' . time();
        }
        Program::create($data);
        clear_old();
        Session::flash('success', 'Program created.');
        redirect('/admin/programs');
    }

    public function edit(string $id): void
    {
        $program = Program::find((int) $id);
        if (!$program) {
            Session::flash('error', 'Program not found.');
            redirect('/admin/programs');
        }
        $this->adminView('admin/programs/form', ['pageTitle' => 'Edit Program', 'program' => $program]);
    }

    public function update(string $id): void
    {
        $program = Program::find((int) $id);
        if (!$program) {
            redirect('/admin/programs');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/programs/edit/' . $id);
        }
        $existing = Program::findBy('slug', $data['slug']);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            $data['slug'] .= '-' . time();
        }
        Program::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'Program updated.');
        redirect('/admin/programs');
    }

    public function destroy(string $id): void
    {
        if (Program::find((int) $id)) {
            Program::delete((int) $id);
            Session::flash('success', 'Program deleted.');
        }
        redirect('/admin/programs');
    }

    private function validated(): ?array
    {
        $data = [
            'name'        => $this->input('name'),
            'slug'        => slugify($this->input('slug') ?: $this->input('name')),
            'level'       => $this->input('level'),
            'description' => $this->input('description'),
            'subjects'    => $this->input('subjects'),
            'sort_order'  => (int) $this->input('sort_order', '0'),
            'status'      => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['name']) < 3 || mb_strlen($data['level']) < 2 || mb_strlen($data['description']) < 20) {
            keep_old($data);
            Session::flash('error', 'A program needs a name, level (e.g. "Grades I – V") and a description of at least 20 characters.');
            return null;
        }
        return $data;
    }
}
