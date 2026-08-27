<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;

final class GalleryController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/gallery/index', [
            'pageTitle' => 'Gallery',
            'albums'    => GalleryAlbum::withCounts(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/gallery/form', ['pageTitle' => 'Add Album', 'album' => null, 'images' => []]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/gallery/create');
        }
        if (GalleryAlbum::findBy('slug', $data['slug'])) {
            $data['slug'] .= '-' . time();
        }
        if ($cover = handle_upload('cover', 'gallery')) {
            $data['cover'] = $cover;
        }
        $id = GalleryAlbum::create($data);
        clear_old();
        Session::flash('success', 'Album created — now add photos to it.');
        redirect('/admin/gallery/edit/' . $id);
    }

    public function edit(string $id): void
    {
        $album = GalleryAlbum::find((int) $id);
        if (!$album) {
            Session::flash('error', 'Album not found.');
            redirect('/admin/gallery');
        }
        $this->adminView('admin/gallery/form', [
            'pageTitle' => 'Edit Album — ' . $album['title'],
            'album'     => $album,
            'images'    => GalleryImage::forAlbum((int) $id),
        ]);
    }

    public function update(string $id): void
    {
        $album = GalleryAlbum::find((int) $id);
        if (!$album) {
            redirect('/admin/gallery');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/gallery/edit/' . $id);
        }
        $existing = GalleryAlbum::findBy('slug', $data['slug']);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            $data['slug'] .= '-' . time();
        }
        if ($cover = handle_upload('cover', 'gallery')) {
            delete_upload_set($album['cover']);
            $data['cover'] = $cover;
        }
        GalleryAlbum::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'Album updated.');
        redirect('/admin/gallery/edit/' . $id);
    }

    public function destroy(string $id): void
    {
        $album = GalleryAlbum::find((int) $id);
        if ($album) {
            foreach (GalleryImage::forAlbum((int) $id) as $img) {
                delete_upload_set($img['image']);
            }
            delete_upload_set($album['cover']);
            GalleryAlbum::delete((int) $id); // images cascade in DB
            Session::flash('success', 'Album and its photos deleted.');
        }
        redirect('/admin/gallery');
    }

    public function addImage(string $id): void
    {
        $album = GalleryAlbum::find((int) $id);
        if (!$album) {
            redirect('/admin/gallery');
        }
        $stored = 0;
        // Multi-file input: images[]
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $_FILES['_single'] = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];
                if ($path = handle_upload('_single', 'gallery')) {
                    GalleryImage::create([
                        'album_id'   => (int) $id,
                        'image'      => $path,
                        'caption'    => $this->input('caption'),
                        'sort_order' => 0,
                    ]);
                    $stored++;
                }
            }
            unset($_FILES['_single']);
        }
        if ($stored > 0) {
            ActivityLog::record('uploaded', 'gallery', $stored . ' photos → ' . $album['title']);
            Session::flash('success', $stored . ' photo' . ($stored > 1 ? 's' : '') . ' uploaded to "' . $album['title'] . '".');
        } elseif (!Session::hasFlash('error')) {
            Session::flash('error', 'No photos were selected.');
        }
        redirect('/admin/gallery/edit/' . $id);
    }

    public function deleteImage(string $id): void
    {
        $img = GalleryImage::find((int) $id);
        if ($img) {
            delete_upload_set($img['image']);
            GalleryImage::delete((int) $id);
            Session::flash('success', 'Photo removed.');
            redirect('/admin/gallery/edit/' . $img['album_id']);
        }
        redirect('/admin/gallery');
    }

    private function validated(): ?array
    {
        $data = [
            'title'       => $this->input('title'),
            'slug'        => slugify($this->input('slug') ?: $this->input('title')),
            'description' => $this->input('description'),
            'sort_order'  => (int) $this->input('sort_order', '0'),
            'status'      => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['title']) < 3) {
            keep_old($data);
            Session::flash('error', 'The album title is required.');
            return null;
        }
        return $data;
    }
}
