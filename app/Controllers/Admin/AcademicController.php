<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\AcademicFramework;
use App\Models\ClassSubject;
use App\Models\GradingScale;

/** Feeds the public Academics page. Term dates live under Admin -> Academic Calendar. */
final class AcademicController extends SectionController
{
    protected static function base(): string
    {
        return '/admin/academics';
    }

    protected static function heading(): string
    {
        return 'Academics';
    }

    protected static function intro(): string
    {
        return 'Content of the public <a href="' . url('/academics') . '" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">Academics page</a>. '
            . 'The heading and introduction of the page itself are edited under <a href="' . url('/admin/pages') . '" class="text-navy font-medium hover:underline">Pages</a>; '
            . 'subject lists come from <a href="' . url('/admin/programs') . '" class="text-navy font-medium hover:underline">Programs</a> and '
            . 'term dates from <a href="' . url('/admin/calendar') . '" class="text-navy font-medium hover:underline">Academic Calendar</a>.';
    }

    protected static function sections(): array
    {
        return [
            'framework' => [
                'model'   => AcademicFramework::class,
                'title'   => 'Framework Block',
                'plural'  => 'Framework',
                'blurb'   => 'The standing rules of teaching, shown as cards at the top of the page.',
                'columns' => [
                    'title' => ['label' => 'Title'],
                    'body'  => ['label' => 'Text'],
                ],
                'fields'  => [
                    ['name' => 'title',  'label' => 'Title', 'required' => true, 'wide' => true, 'max' => 120, 'placeholder' => 'Medium of instruction'],
                    ['name' => 'body',   'label' => 'Text', 'type' => 'textarea', 'rows' => 4, 'required' => true, 'wide' => true, 'max' => 600,
                     'help' => 'Two or three sentences. State what the school does, not what it hopes to do. Maximum 600 characters.'],
                    ['name' => 'icon',   'label' => 'Icon', 'type' => 'select', 'options' => AcademicFramework::ICONS],
                    ['name' => 'accent', 'label' => 'Colour', 'type' => 'select', 'options' => AcademicFramework::ACCENTS],
                ],
            ],
            'grading' => [
                'model'   => GradingScale::class,
                'title'   => 'Grade Band',
                'plural'  => 'Grading Scale',
                'blurb'   => 'How a percentage becomes the grade printed on a report card.',
                'columns' => [
                    'grade'  => ['label' => 'Grade'],
                    'band'   => ['label' => 'Marks'],
                    'remark' => ['label' => 'Remark'],
                ],
                'fields'  => [
                    ['name' => 'grade',  'label' => 'Grade', 'required' => true, 'max' => 8, 'placeholder' => 'A+'],
                    ['name' => 'band',   'label' => 'Marks', 'required' => true, 'max' => 40, 'placeholder' => '80% and above'],
                    ['name' => 'remark', 'label' => 'Remark on the report card', 'wide' => true, 'max' => 120, 'placeholder' => 'Outstanding'],
                ],
            ],
            'classes' => [
                'model'   => ClassSubject::class,
                'title'   => 'Class',
                'plural'  => 'Class Subjects',
                'blurb'   => 'Subjects taught in each class/section. Shown as a table on the public Programs page.',
                'columns' => [
                    'class_name' => ['label' => 'Class'],
                    'subjects'   => ['label' => 'Subjects'],
                ],
                'fields'  => [
                    ['name' => 'class_name', 'label' => 'Class / Section', 'required' => true, 'max' => 80, 'placeholder' => 'Six - Boys'],
                    ['name' => 'subjects',   'label' => 'Subjects', 'type' => 'textarea', 'rows' => 2, 'wide' => true, 'max' => 400,
                     'help' => 'Comma-separated, e.g. "English, Urdu, Math, Science". Leave blank if none assigned yet.'],
                ],
            ],
        ];
    }
}
