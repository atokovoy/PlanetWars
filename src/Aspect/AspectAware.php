<?php
namespace Aspect;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class AspectAware
{
    /**
     * @var Aspect
     */
    private $aspect;

    /**
     * @param Aspect $factory
     */
    public function setAspect(Aspect $factory)
    {
        $this->aspect = $factory;
    }

    /**
     * @return Aspect
     */
    public function getAspect()
    {
        return $this->aspect;
    }
}
