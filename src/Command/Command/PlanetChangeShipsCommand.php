<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class PlanetChangeShipsCommand extends SimpleCommand
{
    protected $planetId;

    protected $numShips;

    public function setPlanet(\Entity\Planet $planet)
    {
        $this->planetId = $planet->getId();
        $this->numShips = $planet->getNumShips();
    }

    public function setNumShips($numShips)
    {
        $this->numShips = $numShips;
    }

    public function getNumShips()
    {
        return $this->numShips;
    }

    public function setPlanetId($planetId)
    {
        $this->planetId = $planetId;
    }

    public function getPlanetId()
    {
        return $this->planetId;
    }

    public function execute(\Player $player, \Registry $registry)
    {
        /**
         * @var $planet \Entity\Planet
         */
        $planet = $registry->getMap()->findOne($this->planetId);
        if (false == $planet) {
            throw new \Exception(sprintf("Unknown planet ID %s", $this->planetId));
        }
        $planet->setNumShips($this->numShips);
    }
}
