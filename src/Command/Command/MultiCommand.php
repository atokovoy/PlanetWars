<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class MultiCommand extends SimpleCommand
{
    protected $commandList = array();

    public function addCommand(\Command\CommandInterface $command)
    {
        $this->commandList[] = $command;
    }

    public function setCommandList(array $commands)
    {
        $this->commandList = $commands;
    }

    public function getCommandList()
    {
        return $this->commandList;
    }


    public function execute(\Player $player, \Registry $registry)
    {
        foreach ($this->commandList as $command) {
            /**
             * @var $command \Command\CommandInterface
             */
            $command->execute($player, $registry);
        }
    }
}
