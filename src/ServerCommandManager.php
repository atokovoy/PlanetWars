<?php
use Event\Event,
    Command\CommandManager,
    Command\CommandInterface,
    Command\Command as CommandImpl;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class ServerCommandManager extends CommandManager
{
    protected function isValid(CommandInterface $command)
    {
        $allow = array(
            'SendFleetCommand',
            'HeartBeatCommand',
            'MultiCommand'
        );

        if ($command->getCommandName() != 'MultiCommand') {
            return in_array($command->getCommandName(), $allow);
        }
        /**
         * @var $command \Command\Command\MultiCommand
         * @var $singleCommand \Command\CommandInterface
         */
        foreach ($command->getCommandList() as $singleCommand) {
            if (false == in_array($singleCommand->getCommandName(), $allow)) {
                return false;
            }
        }
        return true;
    }

    public function handle(Event $event)
    {
        switch ($event->getName()) {
            case Event::FLEET_ADD:
                /**
                 * @var $fleet \Entity\Fleet
                 */
                $fleet = $event->getObject();
                $command = new CommandImpl\AddFleetCommand();
                $command->setFleet($fleet);
                $this->commandPool->addCommandToPlayer($command, $fleet->getPlayerId());
                break;
            case Event::FLEET_MOVE:
                /**
                 * @var $fleet \Entity\Fleet
                 */
                $fleet = $event->getObject();
                $command = new CommandImpl\MoveFleetCommand();
                $command->setFleetId($fleet->getId());
                $this->commandPool->addCommandToPlayer($command, $fleet->getPlayerId());
                break;
            case Event::DO_TURN:
                $command = new CommandImpl\DoTurnCommand();
                $this->commandPool->addCommand($command);
                break;
            case Event::CREATE_WORLD:
                /**
                 *@var $registry \Registry
                 */
                $registry = $event->getObject();
                $command = new CommandImpl\CreateWorldCommand();
                $command->setMap($registry->getMap());
                $this->commandPool->addCommand($command);
                break;
            case Event::CREATE_PLAYER:
                /**
                 * @var $player Player
                 */
                $player = $event->getObject();
                $command = new CommandImpl\CreatePlayerCommand();
                $command->setPlayerId($player->getId());
                $this->commandPool->addCommandToPlayer($command, $player->getId());
                break;
            case Event::PLANET_CHANGE_SHIPS:
                /**
                 * @var $planet \Entity\Planet
                 */
                $planet = $event->getObject();
                $command = new CommandImpl\PlanetChangeShipsCommand();
                $command->setPlanet($planet);
                $this->commandPool->addCommand($command);
                break;
            case Event::PLANET_CHANGE_OWNER:
                /**
                 * @var $planet \Entity\Planet
                 */
                $planet = $event->getObject();
                $command = new CommandImpl\PlanetChangeOwnerCommand();
                $command->setPlanet($planet);
                $this->commandPool->addCommand($command);
                break;
        }
    }
}
