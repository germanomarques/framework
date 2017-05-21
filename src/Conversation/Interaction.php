<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Application\Kernel;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Conversation\Traits\InteractsWithSession;

abstract class Interaction implements Conversable
{
    use InteractsWithSession,
        SendsMessages,
        Transitions;

    /**
     * Run interaction.
     */
    abstract public function run(): void;

    /**
     * Process received message.
     *
     * @param ReceivedMessage $reply
     */
    abstract public function process(ReceivedMessage $reply): void;

    /**
     * Handle interaction.
     *
     * @param Kernel $kernel
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function handle(Kernel $kernel): void
    {
        $this->kernel = $kernel;
        $session = $this->kernel->getSession();

        if ($session->getInteraction() instanceof $this) {
            $this->process($session->getMessage());

            if (!$this->transitioned) {
                $this->kernel->clearSession();
            }
        } else {
            $this->kernel->getSession()->setInteraction($this);
            $this->run();
        }
    }
}
