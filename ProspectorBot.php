<?php
require_once('loader.php');

/**
 *
 * port from starter package
 *
 */
class ProspectorBot extends Client
{
    public function doTurn()
    {

        //Random bot
        // (1) If we current have a fleet in flight, then do nothing until it
        // arrives.
        if (count($this->getMyFleets()) > 0) {
            return;
        }

        // (2) Find my strongest planet.
        $source = null;
        $sourceScore = 0;
        foreach ($this->getMyPlanets() as $planet) {
            $score = $planet->getNumShips() / (1 + $planet->getGrowthRate());
            if ($score > $sourceScore) {
                $sourceScore = $score;
                $source = $planet;
            }
        }

        // (3) Find the weakest enemy or neutral planet.
        $dest = null;
        $destScore = 0;
        foreach ($this->getNotMyPlanets() as $planet) {
            $score = (1 + $planet->getGrowthRate()) / $planet->getNumShips();
            if ($score > $destScore) {
                $destScore = $score;
                $dest = $planet;
            }
        }
        
        // (4) Send half the ships from my strongest planet to the weakest
        // planet that I do not own.
        if ($source != null && $dest != null) {
            $numShips = floor($source->getNumShips() / 2);
            $this->issueOrder($source, $dest, $numShips);
        }

    }
}


$bot = new ProspectorBot();
$bot->setCacheDir('cache');
$bot->connect(8181);
$bot->init();
$bot->run();
if ($bot->getWinnerId() !== null) {
    printf("Player %s won\n", $bot->getWinnerId());
} else {
    print "Server Terminated\n";
}