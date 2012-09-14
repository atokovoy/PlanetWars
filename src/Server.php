<?php

use Entity\Planet,
    Entity\Fleet;
use Event\Event;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Server extends World implements GameInterface
{
    protected $players;

    /**
     * @var CombatLog
     */
    protected $combatLog;

    protected $maxPlayers;

    protected $mapName;

    protected $cacheDir;

    protected $socket;

    protected $mapDefinition;
    
    /**
     * @return \Command\CommandManager
     */
    protected function createCommandManager()
    {
        return new ServerCommandManager();
    }

    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    public function start($port, $ip = '127.0.0.1')
    {
        $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        socket_bind($socket, $ip, $port);
        socket_listen($socket);
        socket_set_nonblock($socket);
        $nPlayers = $this->maxPlayers;
        $this->socket = $socket;
        while($nPlayers) {
            if (($connection = @socket_accept($socket)) !== false) {
                echo "Client has connected\n";
                $transport = new \Transport\SocketTransport($connection);
                $player = new Player();
                $player->setTransport($transport);
                $this->addPlayer($player);
                $nPlayers--;
            }
            sleep(1);
        }
    }

    public function __destruct()
    {
        if ($this->socket) {
            socket_close($this->socket);
        }
    }

    public function stop()
    {
        $this->eventManager->notify(new Event(Event::GAME_OVER, $this->getWinner()));
        foreach ($this->players as $player) {
            $this->commandManager->sendCommands($player);
        }
        $this->commandManager->flushCommands();
    }

    public function init()
    {
        parent::init();

        $this->combatLog = new CombatLog();
        $this->eventManager->subscribe($this->combatLog);

        if ($this->mapDefinition) {
        	$this->map->loadFromDefinitions($this->mapDefinition);
        } else {
            $this->map->load(new SimpleMapLoader(), $this->mapName);
        }

        $this->eventManager->notify(new Event(Event::CREATE_WORLD, $this->registry));
    }

    protected function isAlive(Player $player)
    {
        $playerFleets = $this->fleetManager->findAllByPlayer($player);
        return ($this->map->hasPlanet($player) || !empty($playerFleets));
    }

    protected function getNextPlayerId()
    {
        return count($this->players) + 1;
    }

    public function setMaxPlayers($maxPlayer)
    {
        $this->maxPlayers = $maxPlayer;
    }

    public function setMapName($mapName)
    {
        $this->mapName = $mapName;
    }

    public function setMapDefinition($map)
    {
        $this->mapDefinition = $map;
    }

    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function addPlayer(Player $player)
    {
        $id = $this->getNextPlayerId();
        $player->setId($id);
        $this->players[$id] = $player;
        $this->eventManager->notify(new Event(Event::CREATE_PLAYER, $player));
    }

    public function isGameOver()
    {
        $countLivePlayers = 0;
        foreach ($this->players as $player) {
            if ($this->isAlive($player)) {
                $countLivePlayers++;
            }
        }

        return $countLivePlayers == 1;
    }

    /**
     * @return Player|false
     */
    public function getWinner()
    {
        foreach ($this->players as $player) {
            if ($this->isAlive($player)) {
                return $player;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getCombatLog()
    {
        return $this->combatLog->getLog();
    }

    public function doTurn()
    {
        $this->eventManager->notify(new Event(Event::DO_TURN, $this->registry));
        /**
         * @var $player \Player
         */
        foreach ($this->players as $player) {
            $this->commandManager->sendCommands($player);
        }
        $this->commandManager->flushCommands();

        foreach ($this->players as $player) {
            $this->commandManager->processCommands($player, $this->registry);
        }
        $this->map->doTurn();
        $this->fleetManager->doTurn();
    }
}
