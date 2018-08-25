<?php

declare(strict_types=1);

namespace FondBot;

use Illuminate\Support\ServiceProvider;

class FondBotApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        FondBot::routes();
    }

    protected function intents(): void
    {
        FondBot::intentsIn(app_path('Intents'));
    }
}
