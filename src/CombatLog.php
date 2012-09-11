<?php

use Event\Event,
    Event\Listener;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class CombatLog implements Listener
{
    protected $log = array(
        'map' => array(),
        'combat' => array()
    );

    protected $turnId = -1;

    public function handle(Event $event)
    {
        switch ($event->getName()) {
            case Event::CREATE_WORLD:
                /**
                 *@var $registry \Registry
                 */
                $registry = $event->getObject();
                $planets = $registry->getMap()->getData();
                foreach ($planets as $planet) {
                    /**
                     * @var $planet \Entity\Planet
                     */
                    $def = array(
                        'planetId' => $planet->getId(),
                        'x' => $planet->getX(),
                        'y' => $planet->getY(),
                        'ships' => $planet->getNumShips(),
                        'growth' => $planet->getGrowthRate(),
                        'ownerId' => $planet->getOwnerId()
                    );
                    $this->log['map'][] = $def;
                }
                break;
            case Event::DO_TURN:
                $this->turnId++;
                $this->log['combat'][$this->turnId] = array('turnId' => $this->turnId + 1);
                break;
            case Event::FLEET_ADD:
                /**
                 * @var $fleet \Entity\Fleet
                 */
                $fleet = $event->getObject();
                if (!isset($this->log['combat'][$this->turnId]['order'])) {
                    $this->log['combat'][$this->turnId]['order'] = array();
                }
                $this->log['combat'][$this->turnId]['order'][] = array(
                    'fleetId' => $fleet->getId(),
                    'ownerId' => $fleet->getPlayerId(),
                    'srcPlanet' => $fleet->getSource()->getId(),
                    'targetPlanet' => $fleet->getTarget()->getId(),
                    'ships' => $fleet->getNumShips(),
                    'totalTurns' => $fleet->getTotalTripLength()
                );
                break;
            case Event::FLEET_MOVE:
                /**
                 * @var $fleet \Entity\Fleet
                 */
                $fleet = $event->getObject();
                if (!isset($this->log['combat'][$this->turnId]['fleets'])) {
                    $this->log['combat'][$this->turnId]['fleets'] = array();
                }
                $this->log['combat'][$this->turnId]['fleets'][] = array(
                    'fleetId' => $fleet->getId(),
                    'turns' => $fleet->getTurnsRemaining()
                );
                break;
            case Event::PLANET_CHANGE_SHIPS:
            case Event::PLANET_CHANGE_OWNER:
                /**
                 * @var $planet \Entity\Planet
                 */
                $planet = $event->getObject();
                if (!isset($this->log['combat'][$this->turnId]['planets'])) {
                    $this->log['combat'][$this->turnId]['planets'] = array();
                }
                $found = false;
                $data = array(
                    'planetId' => $planet->getId(),
                    'ownerId' => $planet->getOwnerId(),
                    'ships' => $planet->getNumShips()
                );
                foreach ($this->log['combat'][$this->turnId]['planets'] as $key => &$val) {
                    if ($val['planetId'] == $planet->getId()) {
                        $val = $data;
                        $found = true;
                        break;
                    }
                }
                if (false == $found) {
                    $this->log['combat'][$this->turnId]['planets'][] = $data;
                }
                break;
        }
    }

    public function getLog()
    {
        return $this->log;
    }
}
