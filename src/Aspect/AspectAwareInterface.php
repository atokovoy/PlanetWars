<?php
namespace Aspect;
/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
interface AspectAwareInterface
{
    public function setAspect(Aspect $aspect);
    public function getAspect();
}
