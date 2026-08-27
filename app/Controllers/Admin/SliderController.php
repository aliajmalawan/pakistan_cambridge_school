<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\Slider;

final class SliderController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/sliders/index', [
            'pageTitle' => 'Homepage Sliders',
            'sliders'   => Slider::all('sort_order ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/sliders/form', ['pageTitle' => 'Add Slider', 'slider' => null]);
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/sliders/create');
        }
        if ($image = handle_upload('image', 'sliders', \App\Core\ImageService::WIDTH_HERO)) {
            $data['image'] = $image;
        }
        Slider::create($data);
        clear_old();
        Session::flash('success', 'Slider created.');
        redirect('/admin/sliders');
    }

    public function edit(string $id): void
    {
        $slider = Slider::find((int) $id);
        if (!$slider) {
            Session::flash('error', 'Slider not found.');
            redirect('/admin/sliders');
        }
        $this->adminView('admin/sliders/form', ['pageTitle' => 'Edit Slider', 'slider' => $slider]);
    }

    public function update(string $id): void
    {
        $slider = Slider::find((int) $id);
        if (!$slider) {
            redirect('/admin/sliders');
        }
        $data = $this->validated();
        if ($data === null) {
            redirect('/admin/sliders/edit/' . $id);
        }
        if ($image = handle_upload('image', 'sliders', \App\Core\ImageService::WIDTH_HERO)) {
            delete_upload_set($slider['image']);
            $data['image'] = $image;
        }
        Slider::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'Slider updated.');
        redirect('/admin/sliders');
    }

    public function destroy(string $id): void
    {
        $slider = Slider::find((int) $id);
        if ($slider) {
            delete_upload_set($slider['image']);
            Slider::delete((int) $id);
            Session::flash('success', 'Slider deleted.');
        }
        redirect('/admin/sliders');
    }

    private function validated(): ?array
    {
        $data = [
            'eyebrow'    => $this->input('eyebrow'),
            'title'      => $this->input('title'),
            'subtitle'   => $this->input('subtitle'),
            'cta_text'   => $this->input('cta_text'),
            'cta_link'   => $this->input('cta_link'),
            'sort_order' => (int) $this->input('sort_order', '0'),
            'status'     => $this->input('status') === 'draft' ? 'draft' : 'published',
        ];
        if (mb_strlen($data['title']) < 3) {
            keep_old($data);
            Session::flash('error', 'The slider title is required (minimum 3 characters).');
            return null;
        }
        return $data;
    }
}
