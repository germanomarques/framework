<?php

declare(strict_types=1);

namespace FondBot;

use Illuminate\Filesystem\Cache;
use FondBot\Events\MessageReceived;
use FondBot\Channels\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Conversation\ConversationManager;
use FondBot\Foundation\Listeners\HandleConversation;
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
        $this->registerChannelManager();
        $this->registerEventListeners();
    }

    protected function registerConversationManager(): void
    {
        $this->app->singleton(ConversationManagerContract::class, function () {
            return new ConversationManager($this->app, $this->app[Cache::class]);
        });

        $this->app->alias(ConversationManagerContract::class, ConversationManager::class);
    }

    protected function registerChannelManager(): void
    {
        $this->app->singleton(ChannelManagerContract::class, function () {
            $channels = collect(config('fondbot.channels'))->mapWithKeys(function (array $parameters, string $name) {
                return [$name => $parameters];
            });

            return new ChannelManager($this->app, $channels);
        });

        $this->app->alias(ChannelManagerContract::class, ChannelManager::class);
    }

    protected function registerEventListeners(): void
    {
        /** @var Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(MessageReceived::class, HandleConversation::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
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
}
