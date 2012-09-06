<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class MoveFleetCommand extends SimpleCommand
{
    protected $fleetId;

    public function setFleetId($fleetId)
    {
        $this->fleetId = $fleetId;
    }

    public function getFleetId()
    {
        return $this->fleetId;
    }

    public function execute(\Player $player, \Registry $registry)
    {
        /**
         * @var $fleet \Entity\Fleet
         */
        $fleet = $registry->getFleetManager()->findOneByPlayerId($player->getId(), $this->fleetId);
        if (false == $fleet) {
            throw new \Exception(sprintf("Unknown fleet Id %s", $this->fleetId));
        }
        $fleet->doTurn();
        if ($fleet->getTurnsRemaining() == 0) {
            $registry->getFleetManager()->removeFleet($fleet);
        }
    }
}
