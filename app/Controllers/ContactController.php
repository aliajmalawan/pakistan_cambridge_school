<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\ContactMessage;

final class ContactController extends Controller
{
    public function store(): void
    {
        Csrf::verify();

        $data = [];
        foreach (ContactMessage::EDITABLE as $field) {
            $data[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $errors = ContactMessage::validate($data);
        if ($errors) {
            keep_old($data);
            Session::flash('error', implode(' ', $errors));
            redirect('/contact');
        }

        if ($data['subject'] === '') {
            $data['subject'] = 'General Enquiry';
        }

        ContactMessage::create($data);
        ActivityLog::record('submitted', 'message', $data['subject'] . ' — ' . $data['name'], $data['name']);
        clear_old();

        Session::flash('success', 'Thank you, ' . $data['name'] . ' — your message has been sent. Our office will get back to you soon.');
        redirect('/contact');
    }
}
