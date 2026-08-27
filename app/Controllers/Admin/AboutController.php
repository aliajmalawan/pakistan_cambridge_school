<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Milestone;

/**
 * The one part of the About page with no home of its own. Everything else it
 * shows is edited where it lives: the narrative under Pages, mission and vision
 * under Site Settings, the values under Core Values, and the messages under
 * Leadership.
 */
final class AboutController extends SectionController
{
    protected static function base(): string
    {
        return '/admin/about';
    }

    protected static function heading(): string
    {
        return 'About Page';
    }

    protected static function intro(): string
    {
        return 'The journey timeline on the public <a href="' . url('/page/about') . '" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">About page</a>. '
            . 'The rest of that page is edited where each piece lives: the story under '
            . '<a href="' . url('/admin/pages') . '" class="text-navy font-medium hover:underline">Pages</a>, '
            . 'mission and vision under <a href="' . url('/admin/settings') . '" class="text-navy font-medium hover:underline">Site Settings</a>, '
            . 'the values under <a href="' . url('/admin/values') . '" class="text-navy font-medium hover:underline">Core Values</a>, '
            . 'and the two messages under <a href="' . url('/admin/leadership') . '" class="text-navy font-medium hover:underline">Leadership</a>.';
    }

    protected static function sections(): array
    {
        return [
            'milestones' => [
                'model'   => Milestone::class,
                'title'   => 'Milestone',
                'plural'  => 'Our Journey',
                'blurb'   => 'The school\'s story as dated steps. Shown as a timeline across the About page.',
                'columns' => [
                    'year'   => ['label' => 'Year'],
                    'title'  => ['label' => 'Milestone'],
                    'detail' => ['label' => 'Detail'],
                ],
                'fields'  => [
                    ['name' => 'year',   'label' => 'Year', 'required' => true, 'max' => 16, 'placeholder' => '2014',
                     'help' => 'Free text, so a range like 2014-15 also works.'],
                    ['name' => 'title',  'label' => 'Milestone', 'required' => true, 'max' => 140,
                     'placeholder' => 'College wing established'],
                    ['name' => 'detail', 'label' => 'Detail', 'type' => 'textarea', 'rows' => 3, 'wide' => true, 'max' => 400,
                     'help' => 'One or two sentences. Name something a family can actually see on campus today.'],
                ],
            ],
        ];
    }
}
