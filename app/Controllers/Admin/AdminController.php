<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

abstract class AdminController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            Csrf::verify();
        }
    }
}
