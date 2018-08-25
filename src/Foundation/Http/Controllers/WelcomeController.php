<?php

declare(strict_types=1);

namespace FondBot\Foundation\Http\Controllers;

use FondBot\Foundation\Kernel;
use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    public function index(): string
    {
        return 'FondBot v'.Kernel::VERSION;
    }
}
