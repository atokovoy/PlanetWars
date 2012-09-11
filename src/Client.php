<?php
use Entity\Planet,
    Entity\Fleet;
use Event\Event;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
abstract class Client extends Player implements GameInterface
{
    protected $cacheDir;

    protected $socket;

    protected function createCommandManager()
    {
        return new ClientCommandManager();
    }

    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function connect($port, $ip = '127.0.0.1')
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_block($socket);
        socket_connect($socket, $ip, $port);
        $this->socket = $socket;
        $transport = new \Transport\SocketTransport($socket);
        $this->setTransport($transport);
    }

    public function __destruct()
    {
        if ($this->socket) {
            socket_close($this->socket);
        }
    }

    public function run()
    {
        while ($this->getWinnerId() === null) {
            $this->commandManager->processCommands($this, $this->registry);
            $this->doTurn();
            $this->eventManager->notify(new Event(Event::HEARTBEAT, $this->registry));
            $this->commandManager->sendCommands($this);
            $this->commandManager->flushCommands();
            usleep(100);
        }
    }

    protected function getMyFleets()
    {
        return $this->registry->getFleetManager()->findAllByPlayer($this);
    }

    protected function getMyPlanets()
    {
        return $this->registry->getMap()->findAllByPlayer($this);
    }

    protected function getNotMyPlanets()
    {
        return $this->registry->getMap()->findAllNotOwn($this);
    }

    protected function issueOrder(Planet $source, Planet $target, $numShips)
    {
        $fleet = $this->registry->getFleetManager()->issueOrder($this, $source->getId(), $target->getId(), $numShips);
        if (false == $fleet) {
            throw new \Exception('Wrong order');
        }
        $this->eventManager->notify(new Event(Event::FLEET_SEND, $fleet));
    }
}
