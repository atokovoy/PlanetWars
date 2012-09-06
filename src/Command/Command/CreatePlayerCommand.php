<?php
namespace Command\Command;
/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class CreatePlayerCommand extends SimpleCommand
{
    protected $playerId;

    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;
    }

    public function getPlayerId()
    {
        return $this->playerId;
    }

    public function execute(\Player $player, \Registry $registry)
    {
        $player->setId($this->playerId);
    }
}
