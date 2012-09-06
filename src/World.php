<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class World
{
    protected $players;

    /**
     * @var \Map
     */
    protected $map;

    /**
     * @var CommandManager
     */
    protected $commandManager;

    /**
     * @var \FleetManager
     */
    protected $fleetManager;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var CombatLog
     */
    protected $combatLog;

    protected function isAlive(Player $player)
    {
        return $this->map->hasPlanet($player);
    }

    protected function getNextPlayerId()
    {
        return count($this->players) + 1;
    }

    public function addPlayer(Player $player)
    {
        $id = $this->getNextPlayerId();
        $player->setId($id);
        $this->players[$id] = $player;
        $this->eventManager->notify(new Event\Event(\Event\Event::CREATE_PLAYER, $player));
    }

    public function init()
    {
        $this->eventManager = new EventManager();
        $proxyGenerator = new \Event\ProxyGenerator(__DIR__ . '/../cache');
        $factory = new \Event\ObjectFactory($this->eventManager, $proxyGenerator);

        $this->map = new Map();
        $this->map->setObjectFactory($factory);
        $this->map->load(new SimpleMapLoader(), '../maps/2/map1.txt');

        $this->fleetManager = new FleetManager($this->map, $this->eventManager);
        $this->fleetManager->setObjectFactory($factory);
        $this->commandManager = new ServerCommandManager();
        $this->eventManager->subscribe($this->commandManager);

        $this->combatLog = new CombatLog();
        $this->eventManager->subscribe($this->combatLog);

        $this->registry = new Registry();
        $this->registry->setFleetManager($this->fleetManager);
        $this->registry->setMap($this->map);

        $this->eventManager->notify(new Event\Event(\Event\Event::CREATE_WORLD, $this->registry));
    }

    public function getFleetManager()
    {
        return $this->fleetManager;
    }

    public function isGameOver()
    {
        return false;
        /**
         * @todo Player do nothing for a while
         */
        $countLivePlayers = 0;
        foreach ($this->players as $player) {
            if ($this->isAlive($player)) {
                $countLivePlayers++;
            }
        }

        return $countLivePlayers == 1;
    }

    public function getCombatLog()
    {
        return $this->combatLog->getLog();
    }

    public function doTurn()
    {
        $this->eventManager->notify(new Event\Event(\Event\Event::DO_TURN, $this->registry));
        /**
         * @var $player \Player
         */
        foreach ($this->players as $player) {
            $this->commandManager->sendCommands($player);
        }
        $this->commandManager->flushCommands();

        foreach ($this->players as $player) {
            $this->commandManager->processCommands($player, $this->registry);
        }
        $this->map->doTurn();
        $this->fleetManager->doTurn();
    }
}
