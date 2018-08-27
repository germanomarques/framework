<?php

declare(strict_types=1);

namespace FondBot;

use Illuminate\Support\ServiceProvider;

abstract class FondBotApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->intents();
    }

    /**
     * Register intents.
     */
    abstract protected function intents(): void;
}
