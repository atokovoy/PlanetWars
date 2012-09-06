<?php
use Event\Event,
    Command\CommandManager,
    Command\Command as CommandImpl;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class ClientCommandManager extends CommandManager
{
    public function handle(Event $event)
    {
        switch ($event->getName()) {
            case Event::FLEET_SEND:
                /**
                 * @var $fleet \Entity\Fleet
                 */
                $fleet = $event->getObject();
                $command = new CommandImpl\SendFleetCommand();
                $command->setFleet($fleet);
                $this->commandPool->addCommandToPlayer($command, $fleet->getPlayerId());
                break;
            case Event::HEARTBEAT:
                $command = new CommandImpl\HeartBeatCommand();
                $this->commandPool->addCommand($command);
                break;
        }
    }
}
