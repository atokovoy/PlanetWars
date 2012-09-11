<?php


/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class FleetManager extends \Aspect\AspectAware
{
    protected $fleets = array();

    /**
     * @var \Map
     */
    protected $map;

    /**
     * @var EventManager
     */
    protected $eventManager;

    protected $reachDestination = array();

    protected $nextFleetId = 1;

    /**
     * @param Map $map
     * @param EventManager $eventManager
     */
    public function __construct(Map $map, EventManager $eventManager)
    {
        $this->map = $map;
        $this->eventManager = $eventManager;
    }

    public function addFleet(\Entity\Fleet $fleet, $fleetId = null)
    {
        if (null == $fleetId) {
            $fleetId = $this->nextFleetId;
            $this->nextFleetId++;
        } elseif ($this->nextFleetId <= $fleetId) {
            $this->nextFleetId = $fleetId + 1;
        }
        $fleet->setId($fleetId);
        if (!isset($this->fleets[$fleet->getPlayerId()])) {
            $this->fleets[$fleet->getPlayerId()] = array();
        }
        $this->fleets[$fleet->getPlayerId()][$fleet->getId()] = $fleet;

        $this->eventManager->notify(new \Event\Event(\Event\Event::FLEET_ADD, $fleet));
    }

    public function removeFleet(\Entity\Fleet $fleet)
    {
        unset($this->fleets[$fleet->getPlayerId()][$fleet->getId()]);
    }

    public function findOneByPlayerId($playerId, $fleetId)
    {
        if (!isset($this->fleets[$playerId])) {
            return false;
        }
        if (!isset($this->fleets[$playerId][$fleetId])) {
            return false;
        }

        return $this->fleets[$playerId][$fleetId];
    }

    /**
     * @param Player $player
     * @param $sourceId
     * @param $targetId
     * @param $numShips
     * @return \Entity\Fleet | bool
     */
    public function issueOrder(Player $player, $sourceId, $targetId, $numShips)
    {
        $source = $this->map->findOne($sourceId);
        $target = $this->map->findOne($targetId);
        if ((false == $source) || (false == $target)) {
            return false;
        }

        if ($source->getOwnerId() != $player->getId()) {
            return false;
        }

        if ($source->getNumShips() < $numShips) {
            return false;
        }

        $distance = $this->map->calcDistance($source, $target);

        return $this->createFleet($player->getId(), $source, $target, $numShips, $distance);
    }

    /**
     * @param $playerId
     * @param Entity\Planet $source
     * @param Entity\Planet $target
     * @param $numShips
     * @param $distance
     * @return \Entity\Fleet
     */
    public function createFleet($playerId, \Entity\Planet $source, \Entity\Planet $target, $numShips, $distance)
    {
        $fleet = new \Entity\Fleet($playerId, $source, $target, $numShips, $distance);

        return $this->getAspect()->introduce($fleet);
    }

    /**
     * @param Player $player
     * @return array
     */
    public function findAllByPlayer(Player $player)
    {
        if (!isset($this->fleets[$player->getId()])) {
            return array();
        }

        return $this->fleets[$player->getId()];
    }

    protected function reachDestination(\Entity\Fleet $fleet)
    {
        $targetId = $fleet->getTarget()->getId();
        if (!isset($this->reachDestination[$targetId])) {
            $this->reachDestination[$targetId] = array();
        }
        $this->reachDestination[$targetId][] = $fleet;
    }

    protected function processBattle()
    {
        foreach ($this->reachDestination as $targetId => $fleets) {
            $comFleets = array();
            /**
             * @var $fleet \Entity\Fleet
             */
            foreach ($fleets as $fleet) {
                $playerId = $fleet->getPlayerId();
                if (isset($comFleets[$playerId])) {
                    $comFleets[$playerId] += $fleet->getNumShips();
                } else {
                    $comFleets[$playerId] = $fleet->getNumShips();
                }
            }
            $targetPlanet = $fleet->getTarget();
            $comFleets[0] = $targetPlanet->getNumShips();
            arsort($comFleets);
            //print_r($comFleets);
            while (count($comFleets) > 1) {
                $minFleetShips = array_pop($comFleets);
                //print "Minimum is ". $minFleetShips . "\n";
                foreach ($comFleets as $playerId => &$numShips) {
                    $numShips -= $minFleetShips;
                    if ($numShips <= 0) {
                        unset($comFleets[$playerId]);
                    }
                }
                //print "now battle is: \n";
                //print_r($comFleets);
            }
            //print "And we decide: \n";
            if (count($comFleets) == 0) {
                //draw
                //print "It's a draw \n";
                $targetPlanet->setNumShips(0);
            } else {
                //print "Planet change owner \n";
                $keys = array_keys($comFleets);
                $ownerId = $keys[0];
                $numShips = $comFleets[$ownerId];

                //print $numShips . " " . $ownerId . "\n";
                $targetPlanet->setNumShips($numShips);
                if ($ownerId != $targetPlanet->getOwnerId()) {
                    $targetPlanet->setOwnerId($ownerId);
                }
            }
        }

        $this->reachDestination = array();
    }

    public function doTurn()
    {
        /**
         * @var $fleet \Entity\Fleet
         */
        foreach ($this->fleets as $playerId => $fleets) {
            foreach ($fleets as $fleetId => $fleet) {
                $fleet->doTurn();
                if ($fleet->getTurnsRemaining() == 0) {
                    $this->reachDestination($fleet);
                    $this->removeFleet($fleet);
                }
            }
        }

        $this->processBattle();
    }

    public static function clazz()
    {
        return get_called_class();
    }
}
