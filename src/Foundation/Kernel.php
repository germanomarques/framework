<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Channels\Channel;
use Illuminate\Support\Facades\Route;

class Kernel
{
    public const VERSION = '3.0.13';

    /** @var Channel|null */
    private $channel;

    /**
     * Initialize kernel.
     *
     * @param Channel $channel
     */
    public function initialize(Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * Get current channel.
     *
     * @return Channel|null
     */
    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    /**
     * Register FondBot webhook routes for an application.
     */
    public static function webhookRoutes(): void
    {
        Route::namespace('FondBot\Foundation\Http\Controllers')->group(function () {
            Route::get('/', 'WelcomeController@index');

            Route::group(['middleware' => 'fondbot.webhook'], function () {
                Route::get('/webhook/{channel}/{secret?}', 'WebhookController@store')->name('fondbot.webhook');
                Route::post('/webhook/{channel}/{secret?}', 'WebhookController@store')->name('fondbot.webhook');
            });
        });
    }
}
