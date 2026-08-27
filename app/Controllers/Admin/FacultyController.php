<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\Faculty;

final class FacultyController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/faculty/index', [
            'pageTitle' => 'Faculty',
            'faculty'   => Faculty::all('sort_order ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/faculty/form', ['pageTitle' => 'Add Faculty Member', 'member' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/faculty/create');
        }
        // New members always land at the end of the list, numbered one past
        // whatever the highest sort_order currently is — never left at the
        // form's default of 0, which would jump them ahead of everyone.
        $last = Faculty::where('1=1', [], 'sort_order DESC', 1);
        $data['sort_order'] = $last ? ((int) $last[0]['sort_order'] + 1) : 1;
        if ($photo = handle_upload('photo', 'faculty', \App\Core\ImageService::WIDTH_PORTRAIT)) {
            $data['photo'] = $photo;
        }
        Faculty::create($data);
        clear_old();
        Session::flash('success', 'Faculty member added.');
        redirect('/admin/faculty');
    }

    public function edit(string $id): void
    {
        $member = Faculty::find((int) $id);
        if (!$member) {
            Session::flash('error', 'Faculty member not found.');
            redirect('/admin/faculty');
        }
        $this->adminView('admin/faculty/form', ['pageTitle' => 'Edit Faculty Member', 'member' => $member]);
    }

    public function update(string $id): void
    {
        $member = Faculty::find((int) $id);
        if (!$member) {
            redirect('/admin/faculty');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/faculty/edit/' . $id);
        }
        if ($photo = handle_upload('photo', 'faculty', \App\Core\ImageService::WIDTH_PORTRAIT)) {
            delete_upload_set($member['photo']);
            $data['photo'] = $photo;
        } elseif (upload_cleared('photo')) {
            // No replacement uploaded and the remove box was ticked: drop the file
            // and the reference, so the card falls back to the initials placeholder.
            delete_upload_set($member['photo']);
            $data['photo'] = null;
        }
        Faculty::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'Faculty member updated.');
        redirect('/admin/faculty');
    }

    public function destroy(string $id): void
    {
        $member = Faculty::find((int) $id);
        if ($member) {
            delete_upload_set($member['photo']);
            Faculty::delete((int) $id);
            Session::flash('success', 'Faculty member removed.');
        }
        redirect('/admin/faculty');
    }

    private function validated(): ?array
    {
        $data = [
            'name'          => $this->input('name'),
            'designation'   => $this->input('designation'),
            'department'    => $this->input('department'),
            'qualification' => $this->input('qualification'),
            'bio'           => $this->input('bio'),
            'sort_order'    => (int) $this->input('sort_order', '0'),
            'status'        => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['name']) < 3 || mb_strlen($data['designation']) < 2) {
            keep_old($data);
            Session::flash('error', 'Name and designation are required.');
            return null;
        }
        return $data;
    }
}
