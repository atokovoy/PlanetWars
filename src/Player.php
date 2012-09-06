<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
abstract class Player
{
    /**
     * @var int
     */
    protected $id;

    protected $transport;

    /**
     * @param int $id
     */
    public function __construct(Transport\Transport $transport)
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

    abstract public function doTurn();
}
