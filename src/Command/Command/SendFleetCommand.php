<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class SendFleetCommand extends SimpleCommand
{
    protected $sourcePlanetId;

    protected $targetPlanetId;

    protected $numShips;

    public function setNumShips($numShips)
    {
        $this->numShips = $numShips;
    }

    public function getNumShips()
    {
        return $this->numShips;
    }

    public function setSourcePlanetId($sourcePlanetId)
    {
        $this->sourcePlanetId = $sourcePlanetId;
    }

    public function getSourcePlanetId()
    {
        return $this->sourcePlanetId;
    }

    public function setTargetPlanetId($targetPlanetId)
    {
        $this->targetPlanetId = $targetPlanetId;
    }

    public function getTargetPlanetId()
    {
        return $this->targetPlanetId;
    }

    public function setFleet(\Entity\Fleet $fleet)
    {
        $this->sourcePlanetId = $fleet->getSource()->getId();
        $this->targetPlanetId = $fleet->getTarget()->getId();
        $this->numShips = $fleet->getNumShips();
    }

    public function execute(\Player $player, \Registry $registry)
    {
        /**
         * @var $fleet \Entity\Fleet
         */
        $fleet = $registry->getFleetManager()->issueOrder($player, $this->sourcePlanetId, $this->targetPlanetId, $this->numShips);
        if (false == $fleet) {
            return false;
        }
        $registry->getFleetManager()->addFleet($fleet);
        $fleet->getSource()->removeShips($fleet->getNumShips());
    }
}
