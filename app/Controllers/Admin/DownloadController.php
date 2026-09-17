<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\Download;

final class DownloadController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/downloads/index', [
            'pageTitle' => 'Downloads & Forms',
            'downloads' => Download::all('sort_order ASC, id ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/downloads/form', ['pageTitle' => 'Add Download', 'item' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/downloads/create');
        }
        $last = Download::where('1=1', [], 'sort_order DESC', 1);
        $data['sort_order'] = $last ? ((int) $last[0]['sort_order'] + 1) : 1;

        if ($file = handle_document_upload('file', 'downloads')) {
            $data['file_path'] = $file['path'];
            $data['file_size'] = $file['size'];
            $data['file_ext'] = $file['ext'];
        }
        if (empty($data['file_path']) && empty($data['external_url'])) {
            keep_old($data);
            Session::flash('error', 'Upload a file or provide an external link.');
            redirect('/admin/downloads/create');
        }

        Download::create($data);
        clear_old();
        Session::flash('success', 'Download added.');
        redirect('/admin/downloads');
    }

    public function edit(string $id): void
    {
        $item = Download::find((int) $id);
        if (!$item) {
            Session::flash('error', 'Download not found.');
            redirect('/admin/downloads');
        }
        $this->adminView('admin/downloads/form', ['pageTitle' => 'Edit Download', 'item' => $item]);
    }

    public function update(string $id): void
    {
        $item = Download::find((int) $id);
        if (!$item) {
            redirect('/admin/downloads');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/downloads/edit/' . $id);
        }

        if ($file = handle_document_upload('file', 'downloads')) {
            delete_upload($item['file_path']);
            $data['file_path'] = $file['path'];
            $data['file_size'] = $file['size'];
            $data['file_ext'] = $file['ext'];
        } elseif (upload_cleared('file')) {
            delete_upload($item['file_path']);
            $data['file_path'] = null;
            $data['file_size'] = 0;
            $data['file_ext'] = null;
        }
        if (empty($data['file_path'] ?? $item['file_path']) && empty($data['external_url'])) {
            keep_old($data);
            Session::flash('error', 'Upload a file or provide an external link.');
            redirect('/admin/downloads/edit/' . $id);
        }

        Download::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'Download updated.');
        redirect('/admin/downloads');
    }

    public function destroy(string $id): void
    {
        $item = Download::find((int) $id);
        if ($item) {
            delete_upload($item['file_path']);
            Download::delete((int) $id);
            Session::flash('success', 'Download removed.');
        }
        redirect('/admin/downloads');
    }

    private function validated(): ?array
    {
        $data = [
            'title'         => $this->input('title'),
            'description'   => $this->input('description'),
            'category'      => $this->input('category', 'general'),
            'external_url'  => $this->input('external_url'),
            'status'        => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['title']) < 3) {
            keep_old($data);
            Session::flash('error', 'Title is required (minimum 3 characters).');
            return null;
        }
        if (!isset(Download::CATEGORIES[$data['category']])) {
            keep_old($data);
            Session::flash('error', 'Choose a valid category.');
            return null;
        }
        return $data;
    }
}
