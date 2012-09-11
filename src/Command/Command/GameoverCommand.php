<?php
namespace Command\Command;
/**
 * @copyright 2012 Modera NET2
 * @author Anton Tokovoy <anton.tokovoy@modera.net>
 */
class GameoverCommand extends SimpleCommand
{
    protected $winId;

    public function setWinId($winId)
    {
        $this->winId = $winId;
    }

    public function getWinId()
    {
        return $this->winId;
    }

    public function execute(\Player $player, \Registry $registry)
    {
        $player->setWinnerId($this->winId);
    }
}
