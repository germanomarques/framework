<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Events\MessageReceived;
use FondBot\Channels\ChannelManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Conversation\ConversationManager;
use Illuminate\Cache\Repository as CacheRepository;
use FondBot\Foundation\Listeners\HandleConversation;
use FondBot\Foundation\Http\Middleware\InitializeKernel;
use FondBot\Contracts\Channels\Manager as ChannelManagerContract;
use FondBot\Contracts\Conversation\Manager as ConversationManagerContract;

class FondBotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConversationManager();
        $this->registerEventListeners();
        $this->registerRoutes();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerChannelManager();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeIntentCommand::class,
                Console\MakeInteractionCommand::class,
                Console\MakeActivatorCommand::class,
                Console\ListDriversCommand::class,
                Console\InstallDriverCommand::class,
                Console\ListChannelsCommand::class,
                Console\ListIntentsCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../resources/stubs/FondBotServiceProvider.stub' => app_path('Providers/FondBotServiceProvider.php'),
            ], 'fondbot-provider');

            $this->publishes([
                __DIR__.'/../config/fondbot.php' => config_path('fondbot.php'),
            ], 'fondbot-config');
        }
    }

    /**
     * Register conversation manager.
     */
    protected function registerConversationManager(): void
    {
        $this->app->singleton(ConversationManagerContract::class, function () {
            return new ConversationManager($this->app, $this->app[CacheRepository::class]);
        });

        $this->app->alias(ConversationManagerContract::class, ConversationManager::class);
    }

    /**
     * Register channel manager.
     */
    protected function registerChannelManager(): void
    {
        $this->app->singleton(ChannelManagerContract::class, function ($app) {
            return tap(new ChannelManager($app), function (ChannelManager $manager) {
                $channels = collect(config('fondbot.channels'))
                    ->mapWithKeys(function (array $parameters, string $name) {
                        return [$name => $parameters];
                    })
                    ->toArray();

                $manager->register($channels);
            });
        });

        $this->app->alias(ChannelManagerContract::class, ChannelManager::class);
    }

    /**
     * Register default event listeners.
     */
    protected function registerEventListeners(): void
    {
        /** @var Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(MessageReceived::class, HandleConversation::class);
    }

    /**
     * Register routes.
     */
    protected function registerRoutes(): void
    {
        Route::middleware(InitializeKernel::class)
            ->namespace('FondBot\Foundation\Http\Controllers')
            ->as('fondbot.')
            ->prefix('fondbot')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');
            });
    }
}
