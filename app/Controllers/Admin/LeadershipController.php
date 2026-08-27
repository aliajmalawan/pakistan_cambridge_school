<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\Leadership;

final class LeadershipController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/leadership/index', [
            'pageTitle' => 'Leadership',
            'members'   => Leadership::all('sort_order ASC, id ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/leadership/form', ['pageTitle' => 'Add Leadership Profile', 'member' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/leadership/create');
        }
        if ($photo = handle_upload('photo', 'leadership', \App\Core\ImageService::WIDTH_PORTRAIT)) {
            $data['photo'] = $photo;
        }
        $id = Leadership::create($data);
        $this->enforceSingleFeatured((int) $id, $data['featured']);
        clear_old();
        Session::flash('success', 'Leadership profile added.');
        redirect('/admin/leadership');
    }

    public function edit(string $id): void
    {
        $member = Leadership::find((int) $id);
        if (!$member) {
            Session::flash('error', 'Leadership profile not found.');
            redirect('/admin/leadership');
        }
        $this->adminView('admin/leadership/form', ['pageTitle' => 'Edit Leadership Profile', 'member' => $member]);
    }

    public function update(string $id): void
    {
        $member = Leadership::find((int) $id);
        if (!$member) {
            redirect('/admin/leadership');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/leadership/edit/' . $id);
        }
        if ($photo = handle_upload('photo', 'leadership', \App\Core\ImageService::WIDTH_PORTRAIT)) {
            delete_upload_set($member['photo']);
            $data['photo'] = $photo;
        }
        Leadership::update((int) $id, $data);
        $this->enforceSingleFeatured((int) $id, $data['featured']);
        clear_old();
        Session::flash('success', 'Leadership profile updated.');
        redirect('/admin/leadership');
    }

    public function destroy(string $id): void
    {
        $member = Leadership::find((int) $id);
        if ($member) {
            delete_upload_set($member['photo']);
            Leadership::delete((int) $id);
            Session::flash('success', 'Leadership profile removed.');
        }
        redirect('/admin/leadership');
    }

    /** Only one profile may open the page, so promoting one demotes the rest. */
    private function enforceSingleFeatured(int $id, int $featured): void
    {
        if ($featured === 1) {
            \App\Core\Database::query('UPDATE leadership SET featured = 0 WHERE id != ?', [$id]);
        }
    }

    private function validated(): ?array
    {
        $data = [
            'name'          => $this->input('name'),
            'designation'   => $this->input('designation'),
            'qualification' => $this->input('qualification'),
            'tenure'        => $this->input('tenure'),
            'bio'           => $this->input('bio'),
            'message'       => $this->input('message'),
            'email'         => $this->input('email'),
            'featured'      => $this->input('featured') === '1' ? 1 : 0,
            'sort_order'    => (int) $this->input('sort_order', '0'),
            'status'        => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];

        if (mb_strlen($data['name']) < 3 || mb_strlen($data['designation']) < 2) {
            keep_old($data);
            Session::flash('error', 'Name and designation are required.');
            return null;
        }
        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            keep_old($data);
            Session::flash('error', 'That email address is not valid.');
            return null;
        }
        if ($data['featured'] === 1 && trim(strip_tags($data['message'])) === '') {
            keep_old($data);
            Session::flash('error', 'A featured profile needs a message — that is what opens the page.');
            return null;
        }
        if (mb_strlen($data['bio']) > 500) {
            keep_old($data);
            Session::flash('error', 'The short bio must be 500 characters or fewer.');
            return null;
        }

        return $data;
    }
}
