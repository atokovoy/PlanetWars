<?php
namespace Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class CommandPool
{
    protected $generalPool = array();

    protected $pool = array();

    public function addCommandToPlayer(CommandInterface $command, $playerId)
    {
        if (!isset($this->pool[$playerId])) {
            $this->pool[$playerId] = array();
        }
        $this->pool[$playerId][] = $command;
    }

    public function addCommand(CommandInterface $command)
    {
        $this->generalPool[] = $command;
    }

    public function findAllByPlayer($playerId)
    {
        if (!isset($this->pool[$playerId])) {
            return $this->generalPool;
        }

        return array_merge($this->pool[$playerId], $this->generalPool);
    }

    public function flush()
    {
        $this->generalPool = array();
        $this->pool = array();
    }
}
