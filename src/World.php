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
     * @var Logger
     */
    protected $logger;

    protected $logFilename;

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
     * @return Logger
     */
    public function getLogger()
    {
        if (null == $this->logger) {
            $this->logger = new Logger();
        }

        return $this->logger;
    }

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

        $aspect->createAdvice(FleetManager::clazz(), 'doTurn', $this->createLoggerAdvice("Fleet does next turn", Logger::LEVEL_INFO));
        $aspect->createAdvice(FleetManager::clazz(), 'addFleet', $this->createFleetAwareLoggerAdvice("Add new fleet", Logger::LEVEL_INFO));
        $aspect->createAdvice(FleetManager::clazz(), 'removeFleet', $this->createFleetAwareLoggerAdvice("Land a fleet ", Logger::LEVEL_INFO));

        $aspect->createAdvice(Fleet::clazz(), 'doTurn', $this->createFleetAwareLoggerAdvice("Move the fleet", Logger::LEVEL_INFO));

        $aspect->createAdvice(Planet::clazz(), 'setNumShips', $this->createPlanetAwareLoggerAdvice("Ships quantity were changed via (set)", Logger::LEVEL_INFO));
        $aspect->createAdvice(Planet::clazz(), 'addShips', $this->createPlanetAwareLoggerAdvice("Ships quantity were changed via (add)", Logger::LEVEL_INFO));
        $aspect->createAdvice(Planet::clazz(), 'removeShips', $this->createPlanetAwareLoggerAdvice("Ships quantity were changed (remove)", Logger::LEVEL_INFO));
        $aspect->createAdvice(Planet::clazz(), 'setOwnerId', $this->createPlanetAwareLoggerAdvice("Owner was changed", Logger::LEVEL_INFO));



        return $aspect;
    }

    protected function createEventAdvice(EventManager $eventManager, $eventName)
    {
        $eventAdvice = function($obj) use ($eventManager, $eventName) {
            /**
             * @var $eventManager EventManager
             */
            $event = new \Event\Event($eventName, $obj);
            $eventManager->notify($event);
        };

        return $eventAdvice;
    }

    protected function createPlanetAwareLoggerAdvice($message, $logLevel)
    {
        $logger = $this->getLogger();
        $loggerAdvice = function($planet) use ($logger, $logLevel, $message) {
            /**
             * @var $logger Logger
             * @var $planet \Entity\Planet
             */
            $planetDef = "[id: %s, ownerId: %s, numShips: %s, growthRate: %s, x: %s, y: %s]";
            $planetDef = sprintf($planetDef, $planet->getId(), $planet->getOwnerId(), $planet->getNumShips(),
                $planet->getGrowthRate(), $planet->getX(), $planet->getY()
            );
            $logger->write($message. " " . $planetDef, $logLevel);
        };

        return $loggerAdvice;
    }

    protected function createFleetAwareLoggerAdvice($message, $logLevel)
    {
        $logger = $this->getLogger();
        $loggerAdvice = function($obj, $fleet = null) use ($logger, $logLevel, $message) {
            if (null == $fleet) {
                $fleet = $obj;
            }
            /**
             * @var $logger Logger
             * @var $fleet \Entity\Fleet
             */
            $fleetDef = "[id: %s, playerId: %s, numShips: %s, source: %s, target: %s, totalTripLength: %s, turnsRemaining: %s]";
            $fleetDef = sprintf($fleetDef, $fleet->getId(), $fleet->getPlayerId(), $fleet->getNumShips(),
                $fleet->getSource()->getId(), $fleet->getTarget()->getId(), $fleet->getTotalTripLength(),
                $fleet->getTurnsRemaining()
            );
            $logger->write($message. " " . $fleetDef, $logLevel);
        };

        return $loggerAdvice;
    }

    protected function createLoggerAdvice($message, $logLevel)
    {
        $logger = $this->getLogger();
        $loggerAdvice = function($obj) use ($logger, $logLevel, $message) {
            /**
             * @var $logger Logger
             */
            $logger->write($message, $logLevel);
        };

        return $loggerAdvice;
    }

    public function init()
    {
        /**
         * These two object should be created before we introduce advices
         */
        $this->eventManager = new EventManager();

        $aspect = $this->createAspect($this->getCacheDir());

        $this->map = new Map();
        $this->map->setAspect($aspect);
        $this->map = $aspect->introduce($this->map);


        $this->fleetManager = new FleetManager($this->map, $this->eventManager);
        $this->fleetManager->setAspect($aspect);
        $this->fleetManager = $aspect->introduce($this->fleetManager);

        $this->commandManager = $this->createCommandManager();
        $this->commandManager->setAspect($aspect);
        $this->eventManager->subscribe($this->commandManager);

        $this->registry = new Registry();
        $this->registry->setFleetManager($this->fleetManager);
        $this->registry->setMap($this->map);
    }
}
