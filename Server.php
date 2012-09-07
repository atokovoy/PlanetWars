<?php
require_once('loader.php');

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */

$server = new Server();
$server->setMaxPlayers(1);
$server->setMapName('maps/2/map1.txt');
$server->setCacheDir('cache');
$server->getLogger()->setFilename('log/server.log');
$server->getLogger()->setLogLevel(Logger::LEVEL_ALL);
$server->init();
$server->start(8181);

$limitTurns = 100;
while ($limitTurns) { //!$server->isGameOver()
    $server->doTurn();
    $limitTurns--;
}
//print_r($server->getCombatLog());