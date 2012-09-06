<?php
require_once('loader.php');

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Game
{
    /**
     * @var \World
     */
    protected $world;

    public function initWorld()
    {
        $this->world = new World();
        $this->world->init();
    }

    public function start($port, $ip = '127.0.0.1', $nPlayers = 1)
    {
        $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        socket_bind($socket,'127.0.0.1',$port);
        socket_listen($socket);
        socket_set_nonblock($socket);

        while($nPlayers) {
            if (($connection = @socket_accept($socket)) !== false) {
                echo "Client has connected\n";
                $transport = new \Transport\SocketTransport($connection);
                $player = new Server($transport, $this->world);
                $this->world->addPlayer($player);
                $nPlayers--;
            }
            sleep(1);
        }

        while (false == $this->world->isGameOver()) {
            $this->world->doTurn();
        }
    }
}

$game = new Game();
$game->initWorld();
$game->start(8181);