<?php
namespace Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
interface CommandInterface
{
    public function getCommandName();

    public function toString();

    public function getHeader();

    public function getBody();

    public function execute(\Player $player, \Registry $registry);

    public static function create($string);
}
