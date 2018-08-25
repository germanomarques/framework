<?php

declare(strict_types=1);

namespace FondBot\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use FondBot\Foundation\Kernel;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Controller;
use FondBot\Contracts\Channels\WebhookVerification;

class WebhookController extends Controller
{
    public function store(Kernel $kernel, Dispatcher $events, Request $request)
    {
        $driver = $kernel->getChannel()->getDriver();

        // If driver supports webhook verification
        // We need to check if current request belongs to verification process
        if ($driver instanceof WebhookVerification && $driver->isVerificationRequest($request)) {
            return $driver->verifyWebhook($request);
        }

        // Resolve event from driver and dispatch it
        $events->dispatch(
            $event = $driver->createEvent($request)
        );

        return $driver->createResponse($request, $event);
    }
}
