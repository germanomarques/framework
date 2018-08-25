<?php

declare(strict_types=1);

namespace FondBot\Framework\Http;

use Illuminate\Foundation\Http\Kernel as BaseKernel;
use FondBot\Foundation\Http\Middleware\InitializeKernel;

class Kernel extends BaseKernel
{
    protected $middlewareGroups = [
        'fondbot.webhook' => [
            InitializeKernel::class,
        ],
    ];
}
