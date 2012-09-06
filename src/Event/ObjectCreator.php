<?php
namespace Event;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class ObjectCreator
{
    /**
     * @var ObjectFactory
     */
    private $objectFactory;

    /**
     * @param ObjectFactory $factory
     */
    public function setObjectFactory(ObjectFactory $factory)
    {
        $this->objectFactory = $factory;
    }

    /**
     * @return ObjectFactory
     */
    public function getObjectFactory()
    {
        return $this->objectFactory;
    }
}
