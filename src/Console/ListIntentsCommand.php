<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use FondBot\Contracts\Conversation\Manager;

class ListIntentsCommand extends Command
{
    protected $signature = 'fondbot:intent:list';
    protected $description = 'List all registered intents';

    private $manager;

    public function __construct(Manager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    public function handle(): void
    {
        $rows = collect($this->manager->getIntents())
            ->transform(function ($item) {
                return [$item];
            })
            ->toArray();

        $this->table(['Class'], $rows);
    }
}
