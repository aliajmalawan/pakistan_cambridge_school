<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\AcademicCalendar;
use App\Models\CalendarEvent;

/**
 * Both halves of the Academic Calendar page: the repeating shape of the year
 * (terms and breaks) and the dated events of a particular session.
 */
final class CalendarController extends SectionController
{
    protected static function base(): string
    {
        return '/admin/calendar';
    }

    protected static function heading(): string
    {
        return 'Academic Calendar';
    }

    protected static function intro(): string
    {
        return 'Content of the public <a href="' . url('/academic-calendar') . '" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">Academic Calendar page</a>. '
            . 'Terms and breaks also appear on the <a href="' . url('/academics') . '" class="text-navy font-medium hover:underline" target="_blank" rel="noopener">Academics page</a>. '
            . 'The heading and introduction of the page itself are edited under <a href="' . url('/admin/pages') . '" class="text-navy font-medium hover:underline">Pages</a>.';
    }

    protected static function sections(): array
    {
        return [
            'events' => [
                'model'    => CalendarEvent::class,
                'title'    => 'Calendar Entry',
                'plural'   => 'Dated Events',
                'blurb'    => 'Every holiday, examination window, meeting and deadline of a session. Visitors see the session running today.',
                'sortable' => false,
                'order'    => 'starts_on ASC, id ASC',
                'columns'  => [
                    'starts_on' => ['label' => 'From', 'format' => 'date'],
                    'ends_on'   => ['label' => 'To', 'format' => 'date'],
                    'title'     => ['label' => 'Entry'],
                    'type'      => ['label' => 'Type', 'format' => 'badge'],
                    'session'   => ['label' => 'Session'],
                ],
                'fields'   => [
                    ['name' => 'title',     'label' => 'Entry', 'required' => true, 'wide' => true, 'max' => 160,
                     'placeholder' => 'First term examinations'],
                    ['name' => 'starts_on', 'label' => 'First day', 'type' => 'date', 'required' => true],
                    ['name' => 'ends_on',   'label' => 'Last day', 'type' => 'date',
                     'help' => 'Leave blank for a single day.'],
                    ['name' => 'type',      'label' => 'Type', 'type' => 'select', 'options' => CalendarEvent::TYPES],
                    ['name' => 'session',   'label' => 'Session', 'required' => true, 'max' => 16, 'placeholder' => '2026-27',
                     'help' => 'Groups the year together and drives the session switcher on the page.'],
                    ['name' => 'detail',    'label' => 'Detail', 'type' => 'textarea', 'rows' => 3, 'wide' => true, 'max' => 400,
                     'help' => 'What a guardian needs to know. Say plainly when a date can still move.'],
                ],
            ],
            'terms' => [
                'model'   => AcademicCalendar::class,
                'title'   => 'Term or Break',
                'plural'  => 'Terms and Breaks',
                'blurb'   => 'The repeating shape of the school year, drawn as a timeline. Mark examination windows so they stand out.',
                'columns' => [
                    'term'      => ['label' => 'Term'],
                    'period'    => ['label' => 'When'],
                    'milestone' => ['label' => 'Exams', 'format' => 'flag', 'on' => 'Exams'],
                ],
                'fields'  => [
                    ['name' => 'term',      'label' => 'Term or event', 'required' => true, 'max' => 120, 'placeholder' => 'First term examinations'],
                    ['name' => 'period',    'label' => 'When', 'max' => 80, 'placeholder' => 'Third week of June'],
                    ['name' => 'detail',    'label' => 'Detail', 'type' => 'textarea', 'rows' => 3, 'wide' => true, 'max' => 400,
                     'help' => 'What happens in this part of the year. Maximum 400 characters.'],
                    ['name' => 'milestone', 'label' => 'This is an examination window', 'type' => 'checkbox', 'wide' => true],
                ],
            ],
        ];
    }

    /** A dated entry that ends before it starts is a typo worth catching. */
    protected function afterValidate(array $data, array $spec): ?array
    {
        if (isset($data['starts_on'], $data['ends_on'])
            && $data['ends_on'] !== null && $data['ends_on'] !== ''
            && $data['ends_on'] < $data['starts_on']) {
            return $this->reject($data, 'The last day cannot fall before the first day.');
        }
        return $data;
    }
}
