<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class PlanetChangeOwnerCommand extends SimpleCommand
{
    protected $planetId;

    protected $ownerId;

    public function setPlanet(\Entity\Planet $planet)
    {
        $this->planetId = $planet->getId();
        $this->ownerId = $planet->getOwnerId();
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
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
        $planet->setOwnerId($this->ownerId);
    }
}
