<?php
namespace Event;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Event
{
    const FLEET_SEND = 'fleet_send';
    const FLEET_ADD = 'fleet_add';
    const FLEET_MOVE = 'fleet_move';
    const PLANET_CHANGE_SHIPS = 'planet_change_ships';
    const PLANET_CHANGE_OWNER = 'planet_change_owner';
    const DO_TURN = 'do_turn';
    const HEARTBEAT = 'heartbeat';
    const CREATE_WORLD = 'create_world';
    const CREATE_PLAYER = 'create_player';
    const GAME_OVER = 'game_over';

    protected $name;

    protected $object;

    public function __construct($name, $object)
    {
        $this->name = $name;
        $this->object = $object;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getObject()
    {
        return $this->object;
    }
}
