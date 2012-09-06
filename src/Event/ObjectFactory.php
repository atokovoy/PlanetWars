<?php
namespace Event;

use Entity\Planet,
    Entity\Fleet;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class ObjectFactory
{
    protected $eventManager;

    /**
     * @var ProxyGenerator
     */
    protected $proxyGenerator;

    public function __construct(\EventManager $manager, ProxyGenerator $generator)
    {
        $this->proxyGenerator = $generator;
        $this->eventManager = $manager;
    }

    protected function createProxy($object, array $bindings = array())
    {
        $proxy = $this->proxyGenerator->generateProxy($object, $bindings);
        $proxy->setObserver($this->eventManager);

        return $proxy;
    }

    public function createFleet($playerId, Planet $source,
        Planet $target, $numShips, $totalTripLength)
    {
        $object = new Fleet($playerId, $source, $target, $numShips, $totalTripLength);
        $bindings = array('doTurn' => Event::FLEET_MOVE);

        return $this->createProxy($object, $bindings);
    }

    public function createPlanet($id, $ownerId, $numShips, $growthRate, $x, $y)
    {
        $object = new Planet($id, $ownerId, $numShips, $growthRate, $x, $y);
        $bindings = array(
            'setNumShips' => Event::PLANET_CHANGE_SHIPS,
            'addShips' => Event::PLANET_CHANGE_SHIPS,
            'removeShips' => Event::PLANET_CHANGE_SHIPS,
            'setOwnerId' => Event::PLANET_CHANGE_OWNER
        );

        return $this->createProxy($object, $bindings);
    }
}
