<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\FeeNote;
use App\Models\FeeStructure;

/** Everything on the public Fee Structure page: the table and the two note lists. */
final class FeeController extends SectionController
{
    protected static function base(): string
    {
        return '/admin/fees';
    }

    protected static function heading(): string
    {
        return 'Fee Structure';
    }

    protected static function intro(): string
    {
        return 'Rates and notes on the public <a href="' . url('/fees')
            . '" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">Fee Structure page</a>. '
            . 'The page heading and introduction are edited under <a href="' . url('/admin/pages')
            . '" class="text-navy font-medium hover:underline">Pages</a>. '
            . 'Amounts are in Pakistani Rupees — type digits only, the page adds the formatting.';
    }

    protected static function sections(): array
    {
        return [
            'structure' => [
                'model'   => FeeStructure::class,
                'title'   => 'Class Group',
                'plural'  => 'Fee Table',
                'blurb'   => 'One row per class group, in the order families read them.',
                'columns' => [
                    'class_group'   => ['label' => 'Class group'],
                    'admission_fee' => ['label' => 'Admission', 'format' => 'money'],
                    'monthly_fee'   => ['label' => 'Monthly',   'format' => 'money'],
                    'exam_fee'      => ['label' => 'Exam',      'format' => 'money'],
                ],
                'fields'  => [
                    ['name' => 'class_group',   'label' => 'Class group', 'required' => true, 'wide' => true, 'max' => 80,
                     'placeholder' => 'Grades I – V'],
                    ['name' => 'admission_fee', 'label' => 'Admission fee (one-time)', 'type' => 'number', 'required' => true],
                    ['name' => 'monthly_fee',   'label' => 'Monthly tuition', 'type' => 'number', 'required' => true],
                    ['name' => 'exam_fee',      'label' => 'Examination fee (per term)', 'type' => 'number', 'required' => true],
                    ['name' => 'notes',         'label' => 'Note under the class group', 'wide' => true, 'max' => 200,
                     'help' => 'Small grey line beneath the name, e.g. "Sibling concession applies from the second child". Optional.'],
                ],
            ],

            'concessions' => [
                'model'   => FeeNote::class,
                'filter'  => ['panel' => 'concession'],
                'title'   => 'Concession',
                'plural'  => 'Concessions',
                'blurb'   => 'Bullet points in the left box beneath the fee table.',
                'columns' => ['body' => ['label' => 'Text']],
                'fields'  => [
                    ['name' => 'body', 'label' => 'Text', 'type' => 'textarea', 'rows' => 2,
                     'required' => true, 'wide' => true, 'max' => 300],
                ],
            ],

            'payment' => [
                'model'   => FeeNote::class,
                'filter'  => ['panel' => 'payment'],
                'title'   => 'Payment Note',
                'plural'  => 'Payment',
                'blurb'   => 'Bullet points in the right box: how and when fees are paid.',
                'columns' => ['body' => ['label' => 'Text']],
                'fields'  => [
                    ['name' => 'body', 'label' => 'Text', 'type' => 'textarea', 'rows' => 2,
                     'required' => true, 'wide' => true, 'max' => 300],
                ],
            ],
        ];
    }
}
