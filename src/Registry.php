<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Registry
{
    /**
     * @var FleetManager
     */
    private $fleetManager;

    /**
     * @var Map
     */
    private $map;



    public function setFleetManager(FleetManager $fleetManager)
    {
        $this->fleetManager = $fleetManager;
    }

    /**
     * @return FleetManager
     */
    public function getFleetManager()
    {
        return $this->fleetManager;
    }

    public function setMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }
}
