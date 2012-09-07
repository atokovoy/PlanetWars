<?php
namespace Entity;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Planet
{
    protected $id;

    protected $ownerId;

    protected $numShips;

    protected $growthRate;

    protected $x;

    protected $y;

    /**
     * @param int $id
     * @param int $ownerId
     * @param int $numShips
     * @param int $growthRate
     * @param float $x
     * @param float $y
     */
    public function __construct($id, $ownerId, $numShips, $growthRate, $x, $y)
    {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->numShips = $numShips;
        $this->growthRate = $growthRate;
        $this->x = $x;
        $this->y = $y;
    }

    public function getGrowthRate()
    {
        return $this->growthRate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNumShips()
    {
        return $this->numShips;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    public function setNumShips($numShips)
    {
        $this->numShips = $numShips;
    }

    public function addShips($amount)
    {
        $this->numShips += $amount;
    }

    public function removeShips($amount)
    {
        $this->numShips -= $amount;
    }

    public static function clazz()
    {
        return get_called_class();
    }
}
