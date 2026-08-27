<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;

/**
 * Registry-driven CRUD for admin screens that manage several small tables together.
 *
 * A subclass declares its tables once in sections(); this class provides the six
 * actions, the validation and the shared views for all of them. Adding another
 * table to a screen is one registry entry, not another controller.
 *
 * Each section declares:
 *   model     the App\Models class
 *   title     singular, used in headings and flash messages ("Grade Band")
 *   plural    the panel heading ("Grading Scale")
 *   blurb     one line under the heading explaining what the table drives
 *   columns   column => ['label' => …, 'format' => text|date|badge|chip]
 *   fields    the form, in order; keys match admin/partials/field plus
 *             required, wide, max, and (for selects) options
 */
abstract class SectionController extends AdminController
{
    /** @return array<string, array> keyed by the URL segment for the section */
    abstract protected static function sections(): array;

    /** URL the module lives at, e.g. "/admin/academics". */
    abstract protected static function base(): string;

    /** Heading shown above the list screen. */
    abstract protected static function heading(): string;

    /** Sentence shown above the panels, rendered as trusted HTML so it can carry links. */
    abstract protected static function intro(): string;

    public function index(): void
    {
        $sections = [];
        foreach (static::sections() as $kind => $spec) {
            $model = $spec['model'];
            $order = $spec['order'] ?? 'sort_order ASC, id ASC';

            // A section may show one slice of a shared table (see `filter`), so
            // that two panels can be fed by a single model without two tables.
            $rows = isset($spec['filter'])
                ? $model::where(key($spec['filter']) . ' = ?', [current($spec['filter'])], $order)
                : $model::all($order);

            $sections[$kind] = $spec + ['rows' => $rows];
        }

        $this->adminView('admin/sections/index', [
            'pageTitle' => static::heading(),
            'base'      => static::base(),
            'intro'     => static::intro(),
            'sections'  => $sections,
        ]);
    }

    public function create(string $kind): void
    {
        $spec = $this->spec($kind);
        $this->adminView('admin/sections/form', [
            'pageTitle' => 'Add ' . $spec['title'],
            'base'      => static::base(),
            'kind'      => $kind,
            'spec'      => $spec,
            'row'       => null,
        ]);
    }

    public function store(string $kind): void
    {
        $spec = $this->spec($kind);
        $data = $this->validated($spec);
        if ($data === null) {
            redirect(static::base() . '/' . $kind . '/create');
        }
        ($spec['model'])::create($data);
        clear_old();
        Session::flash('success', $spec['title'] . ' added.');
        redirect(static::base());
    }

    public function edit(string $kind, string $id): void
    {
        $spec = $this->spec($kind);
        $row = ($spec['model'])::find((int) $id);
        if (!$row) {
            Session::flash('error', $spec['title'] . ' not found.');
            redirect(static::base());
        }
        $this->adminView('admin/sections/form', [
            'pageTitle' => 'Edit ' . $spec['title'],
            'base'      => static::base(),
            'kind'      => $kind,
            'spec'      => $spec,
            'row'       => $row,
        ]);
    }

    public function update(string $kind, string $id): void
    {
        $spec = $this->spec($kind);
        if (!($spec['model'])::find((int) $id)) {
            redirect(static::base());
        }
        $data = $this->validated($spec);
        if ($data === null) {
            redirect(static::base() . '/' . $kind . '/edit/' . $id);
        }
        ($spec['model'])::update((int) $id, $data);
        clear_old();
        Session::flash('success', $spec['title'] . ' updated.');
        redirect(static::base());
    }

    public function destroy(string $kind, string $id): void
    {
        $spec = $this->spec($kind);
        if (($spec['model'])::find((int) $id)) {
            ($spec['model'])::delete((int) $id);
            Session::flash('success', $spec['title'] . ' removed.');
        }
        redirect(static::base());
    }

    /** A section that does not exist in the URL sends the user back, not to a blank form. */
    protected function spec(string $kind): array
    {
        $sections = static::sections();
        if (!isset($sections[$kind])) {
            Session::flash('error', 'No such section.');
            redirect(static::base());
        }
        return $sections[$kind];
    }

    protected function validated(array $spec): ?array
    {
        $data = [];
        foreach ($spec['fields'] as $field) {
            $name = $field['name'];
            $data[$name] = ($field['type'] ?? 'text') === 'checkbox'
                ? ($this->input($name) === '1' ? 1 : 0)
                : $this->input($name);
        }
        if (!empty($spec['sortable'] ?? true)) {
            $data['sort_order'] = (int) $this->input('sort_order', '0');
        }
        $data['status'] = $this->input('status') === 'draft' ? 'draft' : 'published';

        // The discriminator is set by the section, not by the form, so a row
        // cannot be posted into a panel it does not belong to.
        if (isset($spec['filter'])) {
            $data[key($spec['filter'])] = current($spec['filter']);
        }

        // Everything the user typed survives a rejection, not just the fields checked so far.
        foreach ($spec['fields'] as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';
            $value = (string) $data[$name];

            if (!empty($field['required']) && $value === '') {
                return $this->reject($data, $field['label'] . ' is required.');
            }
            if (isset($field['options']) && $value !== '' && !isset($field['options'][$value])) {
                return $this->reject($data, 'Choose a valid ' . strtolower($field['label']) . '.');
            }
            if (isset($field['max']) && mb_strlen($value) > $field['max']) {
                return $this->reject($data, $field['label'] . ' must be ' . $field['max'] . ' characters or fewer.');
            }
            if ($type === 'date' && $value !== '' && !$this->isDate($value)) {
                return $this->reject($data, $field['label'] . ' must be a real date.');
            }
            // An optional date left blank is NULL, not the zero date.
            if ($type === 'date' && $value === '' && empty($field['required'])) {
                $data[$name] = null;
            }
        }

        return $this->afterValidate($data, $spec);
    }

    /** Hook for a subclass that needs a rule spanning more than one field. */
    protected function afterValidate(array $data, array $spec): ?array
    {
        return $data;
    }

    protected function reject(array $data, string $message): null
    {
        keep_old($data);
        Session::flash('error', $message);
        return null;
    }

    protected function isDate(string $value): bool
    {
        $d = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
        return $d !== false && $d->format('Y-m-d') === $value;
    }
}
