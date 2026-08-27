<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\Testimonial;

final class TestimonialController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/testimonials/index', [
            'pageTitle'    => 'Testimonials',
            'testimonials' => Testimonial::all('sort_order ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/testimonials/form', ['pageTitle' => 'Add Testimonial', 'testimonial' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/testimonials/create');
        }
        if ($photo = handle_upload('photo', 'testimonials', \App\Core\ImageService::WIDTH_AVATAR)) {
            $data['photo'] = $photo;
        }
        Testimonial::create($data);
        clear_old();
        Session::flash('success', 'Testimonial added.');
        redirect('/admin/testimonials');
    }

    public function edit(string $id): void
    {
        $testimonial = Testimonial::find((int) $id);
        if (!$testimonial) {
            Session::flash('error', 'Testimonial not found.');
            redirect('/admin/testimonials');
        }
        $this->adminView('admin/testimonials/form', ['pageTitle' => 'Edit Testimonial', 'testimonial' => $testimonial]);
    }

    public function update(string $id): void
    {
        $testimonial = Testimonial::find((int) $id);
        if (!$testimonial) {
            redirect('/admin/testimonials');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/testimonials/edit/' . $id);
        }
        if ($photo = handle_upload('photo', 'testimonials', \App\Core\ImageService::WIDTH_AVATAR)) {
            delete_upload_set($testimonial['photo']);
            $data['photo'] = $photo;
        }
        Testimonial::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'Testimonial updated.');
        redirect('/admin/testimonials');
    }

    public function destroy(string $id): void
    {
        $testimonial = Testimonial::find((int) $id);
        if ($testimonial) {
            delete_upload_set($testimonial['photo']);
            Testimonial::delete((int) $id);
            Session::flash('success', 'Testimonial deleted.');
        }
        redirect('/admin/testimonials');
    }

    private function validated(): ?array
    {
        $data = [
            'name'       => $this->input('name'),
            'role'       => $this->input('role'),
            'content'    => $this->input('content'),
            'sort_order' => (int) $this->input('sort_order', '0'),
            'status'     => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['name']) < 3 || mb_strlen($data['content']) < 15) {
            keep_old($data);
            Session::flash('error', 'A testimonial needs a name and the quote itself (at least 15 characters).');
            return null;
        }
        return $data;
    }
}
