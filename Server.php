<?php
require_once('loader.php');

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */

$server = new Server();
$server->setMaxPlayers(2);
$server->setMapName('maps/2/map1.txt');
$server->setCacheDir('cache');
$server->getLogger()->setFilename('log/server.log');
$server->getLogger()->setLogLevel(Logger::LEVEL_ALL);
$server->init();
$server->start(8181);


$limitTurns = 1000;
$turn = 0;
while (($turn < $limitTurns) && !$server->isGameOver()) { //!$server->isGameOver()
    $server->doTurn();
    $turn++;
//    file_put_contents('combat.log', Util\NiceJsonConverter::convert($server->getCombatLog()));
    print "\rTurn: " . $turn;
}
print "\nStop the server\n";
$server->stop();
if (!$server->isGameOver()) {
    print "Reach turns limit\n";
} else {
    printf("Player ID %s won\n", $server->getWinner()->getId());
}

//print_r($server->getCombatLog());