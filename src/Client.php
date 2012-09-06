<?php
require_once('loader.php');

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Client extends Player
{
    /**
     * @var CommandManager
     */
    protected $commandManager;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct($port, $ip = '127.0.0.1')
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_block($socket);
        socket_connect($socket, $ip, $port);
        $transport = new \Transport\SocketTransport($socket);
        parent::__construct($transport);
    }

    public function init()
    {
        $this->eventManager = new EventManager();
        $proxyGenerator = new \Event\ProxyGenerator(__DIR__ . '/../cache');
        $factory = new \Event\ObjectFactory($this->eventManager, $proxyGenerator);

        $map = new Map();
        $map->setObjectFactory($factory);

        $fleetManager = new FleetManager($map, $this->eventManager);
        $fleetManager->setObjectFactory($factory);
        $this->commandManager = new ClientCommandManager();
        $this->eventManager->subscribe($this->commandManager);

        $this->registry = new Registry();
        $this->registry->setFleetManager($fleetManager);
        $this->registry->setMap($map);
    }

    public function run()
    {
        while (true) {
            $this->commandManager->processCommands($this, $this->registry);
            $this->doTurn();
            $this->eventManager->notify(new \Event\Event(\Event\Event::HEARTBEAT, $this->registry));
            $this->commandManager->sendCommands($this);
            $this->commandManager->flushCommands();
            sleep(1);
        }
    }

    protected function getMyFleets()
    {
        return $this->registry->getFleetManager()->findAllByPlayer($this);
    }

    protected function getMyPlanets()
    {
        return $this->registry->getMap()->findAllByPlayer($this);
    }

    protected function getNotMyPlanets()
    {
        return $this->registry->getMap()->findAllNotOwn($this);
    }

    protected function issueOrder(\Entity\Planet $source, \Entity\Planet $target, $numShips)
    {
        $fleet = $this->registry->getFleetManager()->issueOrder($this, $source->getId(), $target->getId(), $numShips);
        if (false == $fleet) {
            throw new \Exception('Wrong order');
        }
        $this->eventManager->notify(new \Event\Event(\Event\Event::FLEET_SEND, $fleet));
    }

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

            $numShips = $source->getNumShips() / 2;
            $this->issueOrder($source, $target, $numShips);
        }
    }
}

$client = new Client(8181);
$client->init();
$client->run();