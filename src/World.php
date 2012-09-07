<?php
use Entity\Planet,
    Entity\Fleet;
use Event\Event;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
abstract class World
{
    /**
     * @var \Map
     */
    protected $map;

    /**
     * @var \Command\CommandManager
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
     * @abstract
     * @return \Command\CommandManager
     */
    abstract protected function createCommandManager();

    /**
     * @abstract
     * @return string
     */
    abstract public function getCacheDir();

    /**
     * @param $cacheDir
     * @return Aspect\Aspect
     */
    protected function createAspect($cacheDir)
    {
        $proxyGenerator = new \Aspect\ProxyGenerator($cacheDir);
        $aspect = new Aspect\Aspect($proxyGenerator);

        $aspect->createAdvice(Fleet::clazz(), 'doTurn', $this->createEventAdvice($this->eventManager, Event::FLEET_MOVE));

        $aspect->createAdvice(Planet::clazz(), 'setNumShips', $this->createEventAdvice($this->eventManager, Event::PLANET_CHANGE_SHIPS));
        $aspect->createAdvice(Planet::clazz(), 'addShips', $this->createEventAdvice($this->eventManager, Event::PLANET_CHANGE_SHIPS));
        $aspect->createAdvice(Planet::clazz(), 'removeShips', $this->createEventAdvice($this->eventManager, Event::PLANET_CHANGE_SHIPS));
        $aspect->createAdvice(Planet::clazz(), 'setOwnerId', $this->createEventAdvice($this->eventManager, Event::PLANET_CHANGE_OWNER));

        return $aspect;
    }

    protected function createEventAdvice($eventManager, $eventName)
    {
        $eventAdvice = function($obj) use ($eventManager, $eventName) {
            $event = new \Event\Event($eventName, $obj);
            $eventManager->notify($event);
        };

        return $eventAdvice;
    }

    public function init()
    {
        $this->eventManager = new EventManager();

        $aspect = $this->createAspect($this->getCacheDir());

        $this->map = new Map();
        $this->map->setAspect($aspect);

        $this->fleetManager = new FleetManager($this->map, $this->eventManager);
        $this->fleetManager->setAspect($aspect);

        $this->commandManager = $this->createCommandManager();
        $this->commandManager->setAspect($aspect);
        $this->eventManager->subscribe($this->commandManager);

        $this->registry = new Registry();
        $this->registry->setFleetManager($this->fleetManager);
        $this->registry->setMap($this->map);
    }
}
