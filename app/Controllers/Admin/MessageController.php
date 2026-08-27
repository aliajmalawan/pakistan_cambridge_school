<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Models\ContactMessage;

final class MessageController extends AdminController
{
    public function index(): void
    {
        $this->adminView('admin/messages/index', [
            'pageTitle' => 'Contact Messages',
            'paged'     => ContactMessage::paginate($this->pageParam(), ADMIN_PER_PAGE, '1', [], 'created_at DESC'),
        ]);
    }

    public function show(string $id): void
    {
        $message = ContactMessage::find((int) $id);
        if (!$message) {
            Session::flash('error', 'Message not found.');
            redirect('/admin/messages');
        }
        if (!$message['is_read']) {
            ContactMessage::update((int) $id, ['is_read' => 1]);
            $message['is_read'] = 1;
        }
        $this->adminView('admin/messages/show', [
            'pageTitle' => 'Message — ' . $message['subject'],
            'message'   => $message,
        ]);
    }

    public function destroy(string $id): void
    {
        if (ContactMessage::find((int) $id)) {
            ContactMessage::delete((int) $id);
            Session::flash('success', 'Message deleted.');
        }
        redirect('/admin/messages');
    }
}
