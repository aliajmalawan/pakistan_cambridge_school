<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Facility;

/** Feeds the public Facilities page (Campus Life → Facilities). */
final class FacilityController extends SectionController
{
    protected static function base(): string
    {
        return '/admin/facilities';
    }

    protected static function heading(): string
    {
        return 'Facilities';
    }

    protected static function intro(): string
    {
        return 'Cards shown on the public <a href="' . url('/facilities') . '" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">Facilities page</a>. '
            . 'The heading and introduction of the page itself are edited under <a href="' . url('/admin/pages') . '" class="text-navy font-medium hover:underline">Pages</a>.';
    }

    protected static function sections(): array
    {
        return [
            'items' => [
                'model'   => Facility::class,
                'title'   => 'Facility',
                'plural'  => 'Campus Facilities',
                'blurb'   => 'One card per facility — laboratories, library, transport, sports grounds and so on.',
                'columns' => [
                    'title'       => ['label' => 'Facility'],
                    'description' => ['label' => 'Description'],
                ],
                'fields'  => [
                    ['name' => 'title',       'label' => 'Facility Name', 'required' => true, 'max' => 120, 'placeholder' => 'Science Laboratories'],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3, 'wide' => true, 'max' => 400,
                     'help' => 'One or two sentences a family would actually find useful.'],
                    ['name' => 'icon',        'label' => 'Icon', 'type' => 'select', 'options' => Facility::ICONS],
                ],
            ],
        ];
    }
}
