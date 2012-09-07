<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class AddFleetCommand extends SimpleCommand
{
    protected $fleetId;

    protected $sourceId;

    protected $targetId;

    protected $numShips;

    protected $distance;

    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function setFleetId($fleetId)
    {
        $this->fleetId = $fleetId;
    }

    public function getFleetId()
    {
        return $this->fleetId;
    }

    public function setNumShips($numShips)
    {
        $this->numShips = $numShips;
    }

    public function getNumShips()
    {
        return $this->numShips;
    }

    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }

    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    public function getTargetId()
    {
        return $this->targetId;
    }

    public function setFleet(\Entity\Fleet $fleet)
    {
        $this->fleetId = $fleet->getId();
        $this->sourceId = $fleet->getSource()->getId();
        $this->targetId = $fleet->getTarget()->getId();
        $this->numShips = $fleet->getNumShips();
        $this->distance = $fleet->getTotalTripLength();
    }

    public function execute(\Player $player, \Registry $registry)
    {
        $source = $registry->getMap()->findOne($this->sourceId);
        if (false == $source) {
            throw new \Exception(sprintf("Unknown Planet ID %s", $this->sourceId));
        }
        $target = $registry->getMap()->findOne($this->targetId);
        if (false == $target) {
            throw new \Exception(sprintf("Unknown Planet ID %s", $this->targetId));
        }
        $fleet = $registry->getFleetManager()->createFleet($player->getId(), $source, $target, $this->numShips, $this->distance);

        $registry->getFleetManager()->addFleet($fleet, $this->fleetId);
    }
}
