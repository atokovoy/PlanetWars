<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Server extends Player
{
    /**
     * @var \World
     */
    protected $world;

    public function __construct(\Transport\Transport $transport, World $world)
    {
        parent::__construct($transport);
        $this->world = $world;
    }

    public function doTurn()
    {
        // TODO: Implement doTurn() method.
    }
}
