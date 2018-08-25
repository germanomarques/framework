<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use FondBot\Foundation\Kernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function map(): void
    {
        Kernel::webhookRoutes();
    }
}
