<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class AddFleetCommand extends SimpleCommand
{
    /**
     * @var \Entity\Fleet
     */
    protected $fleet;

    public function setFleet(\Entity\Fleet $fleet)
    {
        $this->fleet = $fleet;
    }

    public function getFleet()
    {
        return $this->fleet;
    }

    public function execute(\Player $player, \Registry $registry)
    {
        $registry->getFleetManager()->addFleet($this->fleet, $this->fleet->getId());
    }
}
