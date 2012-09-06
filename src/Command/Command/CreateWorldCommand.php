<?php
namespace Command\Command;
/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class CreateWorldCommand extends SimpleCommand
{
    protected $planetDefinition = array();

    public function setMap(\Map $map)
    {
        $data = $map->getData();
        foreach ($data as $planet) {
            $definition = $map->getPlanetDefinition($planet);
            $this->planetDefinition[] = $definition;
        }
    }

    public function setPlanetDefinition(array $def)
    {
        $this->planetDefinition = $def;
    }

    public function getPlanetDefinition()
    {
        return $this->planetDefinition;
    }

    public function execute(\Player $player, \Registry $registry)
    {
        $map = $registry->getMap();
        foreach ($this->planetDefinition as $planetDefinition) {
            $planet = $map->createPlanet($planetDefinition);
            $map->addPlanet($planet);
        }
    }
}
