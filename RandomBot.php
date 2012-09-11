<?php
require_once('loader.php');

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class RandomBot extends Client
{
    public function doTurn()
    {
        //Random bot
        // (1) If we current have a fleet in flight, then do nothing until it
        // arrives.
        if (count($this->getMyFleets()) > 0) {
            return;
        }

        $myPlanets = $this->getMyPlanets();
        $targetPlanets = $this->getNotMyPlanets();

        if (!count($myPlanets) || !count($targetPlanets)) {
            return;
        }

        /**
         * @var $source \Entity\Planet
         * @var $target \Entity\Planet
         */
        // (2) Pick my planet at random.
        $source = array_rand($myPlanets);
        $source = $myPlanets[$source];

        // (3) Pick a target planet at random.
        $target = array_rand($targetPlanets);
        $target = $targetPlanets[$target];

        // (4) Send half the ships from source to dest.
        if ($source != null && $target != null) {

            $numShips = floor($source->getNumShips() / 2);
            $this->issueOrder($source, $target, $numShips);
        }
    }
}


$bot = new RandomBot();
$bot->setCacheDir('cache');
$bot->connect(8181);
$bot->init();
$bot->run();
if ($bot->getWinnerId() !== null) {
    printf("Player %s won\n", $bot->getWinnerId());
} else {
    print "Server Terminated\n";
}