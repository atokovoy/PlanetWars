<?php
namespace Entity;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Fleet
{
    protected $id;

    protected $playerId;

    protected $numShips;

    /**
     * @var Planet
     */
    protected $source;

    /**
     * @var Planet
     */
    protected $target;

    /**
     * @var int
     */
    protected $totalTripLength;

    /**
     * @var int
     */
    protected $turnsRemaining;

    /**
     * @param int $playerId
     * @param Planet $source
     * @param Planet $target
     * @param int $numShips
     * @param int $totalTripLength
     */
    public function __construct($playerId, Planet $source,
        Planet $target, $numShips, $totalTripLength)
    {
        $this->playerId = $playerId;
        $this->numShips = $numShips;
        $this->source = $source;
        $this->target = $target;
        $this->turnsRemaining = $this->totalTripLength = $totalTripLength;
    }

    /**
     * @return Planet
     */
    public function getTarget()
    {
        return $this->target;
    }

    public function getNumShips()
    {
        return $this->numShips;
    }

    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @return Planet
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return int
     */
    public function getTotalTripLength()
    {
        return $this->totalTripLength;
    }

    /**
     * @return int
     */
    public function getTurnsRemaining()
    {
        return $this->turnsRemaining;
    }

    public function removeShips($amount)
    {
        $this->numShips -= $amount;
    }

    public function doTurn()
    {
        if ($this->turnsRemaining) {
            $this->turnsRemaining--;
        } else {
            $this->turnsRemaining = 0;
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function clazz()
    {
        return get_called_class();
    }
}
