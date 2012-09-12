 ___ _               _ __      __
| _ \ |__ _ _ _  ___| |\ \    / /_ _ _ _ ___
|  _/ / _` | ' \/ -_)  _\ \/\/ / _` | '_(_-<
|_| |_\__,_|_||_\___|\__|\_/\_/\__,_|_| /__/

-----------------------------------------------

<?php

require_once('loader.php');

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>, Stanislav Chychkan <ch.stas@gmail.com>
 */

// configs manager
$confManager = new ConfigManager(ConfigManager::SOCKETS);
$confManager->setCacheDir('cache');

print "\rWaiting for ready game configuration...";
while(true) {

    // check for ready configuration 
	$ready = $confManager->getWaitedForLog();
	if (!$ready) {
		sleep(1);
		continue;
	}

    print "\rConfig found! Waiting for teams...\n";
	
	$server = new Server();
	$server->setMaxPlayers($ready->getTeamsCount()-1);
	$server->setMapDefinition($ready->getMap());
	$server->setCacheDir('cache');
	$server->init();
	$server->start(8181, '127.0.0.1');
	

    $limitTurns = 5000;
    $turn = 0;
    while (($turn < $limitTurns) && !$server->isGameOver()) {
        $server->doTurn();
        $turn++;
        print "\rTurn: " . $turn;
    }
    print "\rStop the server\n";
    $server->stop();
    if (!$server->isGameOver()) {
        print "Reach turns limit\n";
    } else {
        printf("Player ID %s won\n", $server->getWinner()->getId());
    }


    $ready->setLog($server->getCombatLog());

    $confManager->save($ready);

}

