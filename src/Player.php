<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Player extends World
{
    /**
     * @var int
     */
    protected $id;

    protected $winnerId;

    /**
     * @var Transport\Transport
     */
    protected $transport;

    public function setTransport(Transport\Transport $transport)
    {
        $this->transport = $transport;
    }

    public function sendCommandDef($data)
    {
        $this->transport->send($data);
    }

    public function receiveCommandDef()
    {
        return $this->transport->receive();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setWinnerId($id)
    {
        $this->winnerId = $id;
    }

    public function getWinnerId()
    {
        return $this->winnerId;
    }

    /**
     * @return \Command\CommandManager
     */
    protected function createCommandManager()
    {
        // TODO: Implement createCommandManager() method. Abstract here
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        // TODO: Implement getCacheDir() method.
    }
}
