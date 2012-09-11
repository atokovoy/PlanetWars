<?php
use Entity\Planet;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Map extends \Aspect\AspectAware
{
    protected $planets = array();

    protected function getNextPlanetId()
    {
        return count($this->planets);
    }

    public function load(SimpleMapLoader $loader, $mapFile)
    {
        $loader->load($mapFile);
        foreach ($loader as $planetDefinition) {
            $planetDefinition['id'] = $this->getNextPlanetId();
            $planet = $this->createPlanet($planetDefinition);
            $this->planets[$planet->getId()] = $planet;
        }
    }

    /**
     * @param \Player $player
     * @return bool
     */
    public function hasPlanet(Player $player)
    {
        /**
         * @var $planet \Entity\Planet
         */
        foreach ($this->planets as $planet) {
            if ($planet->getOwnerId() == $player->getId()) {
                return true;
            }
        }

        return false;
    }

    public function addPlanet(\Entity\Planet $planet)
    {
        $this->planets[$planet->getId()] = $planet;
    }

    /**
     * @param $planetDefinition
     * @return \Entity\Planet
     */
    public function createPlanet($planetDefinition)
    {
        $planet = new Planet(
            $planetDefinition['id'],
            $planetDefinition['player'],
            $planetDefinition['ships'],
            $planetDefinition['rate'],
            $planetDefinition['x'],
            $planetDefinition['y']
        );

        return $this->getAspect()->introduce($planet);
    }

    public function getPlanetDefinition(\Entity\Planet $planet)
    {
        return array(
            'id' => $planet->getId(),
            'player' => $planet->getOwnerId(),
            'ships' => $planet->getNumShips(),
            'rate' => $planet->getGrowthRate(),
            'x' => $planet->getX(),
            'y' => $planet->getY()
        );
    }

    /**
     * @param $planetId
     * @return \Planet $planet|false
     */
    public function findOne($planetId)
    {
        if (!isset($this->planets[$planetId])) {
            return false;
        }

        return $this->planets[$planetId];
    }

    public function findAllByPlayer(Player $player)
    {
        $result = array();
        foreach ($this->planets as $planet) {
            /**
             * @var $planet \Entity\Planet
             */
            if ($planet->getOwnerId() == $player->getId()) {
                $result[] = $planet;
            }
        }

        return $result;
    }

    public function findAllNotOwn(Player $player)
    {
        $result = array();
        foreach ($this->planets as $planet) {
            /**
             * @var $planet \Entity\Planet
             */
            if ($planet->getOwnerId() != $player->getId()) {
                $result[] = $planet;
            }
        }

        return $result;
    }

    /**
     * @param Planet $src
     * @param Planet $dst
     * @return int Distance between two planets
     */
    public function calcDistance(Planet $src, Planet $dst)
    {
        $dx = $src->getX() - $dst->getY();
        $dy = $src->getY() - $dst->getY();

        return ceil(sqrt($dx * $dx + $dy * $dy));
    }

    /**
     * @param $playerId
     * @return int Production of the given player
     */
    public function calcProduction($playerId)
    {
        $production = 0;

        /**
         * @var $planet Planet
         */
        foreach ($this->planets as $planet) {
            if ($planet->getOwnerId() != $playerId) {
                continue;
            }
            $production += $planet->getGrowthRate();
        }

        return $production;
    }

    public function getData()
    {
        return $this->planets;
    }

    public function doTurn()
    {
        /**
         * @var $planet \Entity\Planet
         */
        foreach ($this->planets as $planet) {
            if ($planet->getGrowthRate() == 0) {
                continue;
            }
            $planet->addShips($planet->getGrowthRate());
        }
    }
}
