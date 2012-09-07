<?php
namespace Aspect;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Aspect
{
    protected $advices;

    /**
     * @var ProxyGenerator
     */
    protected $proxyGenerator;

    public function __construct(ProxyGenerator $proxyGenerator)
    {
        $this->proxyGenerator = $proxyGenerator;
    }

    /**
     * @param $object
     * @return array
     */
    protected function getJoinPoints($object)
    {
        $className = get_class($object);
        if (!isset($this->advices[$className])) {
            return array();
        }

        return array_keys($this->advices[$className]);
    }

    /**
     * @param $className
     * @param $methodName
     * @param $advice Callable
     */
    public function createAdvice($className, $methodName, $advice)
    {
        if (!isset($this->advices[$className])) {
            $this->advices[$className] = array();
        }
        if (!is_callable($advice)) {
            throw new \Exception("Invalid aspect advice");
        }

        $this->advices[$className][$methodName] = $advice;
    }

    public function callAdvice($className, $methodName, array $args)
    {
        if (!isset($this->advices[$className][$methodName])) {
            throw new \Exception(sprintf("Unknown advice %s, %s", $className, $methodName));
        }

        $advice = $this->advices[$className][$methodName];
        call_user_func_array($advice, $args);
    }

    public function introduce($object)
    {
        $joinPoints = $this->getJoinPoints($object);
        if (empty($joinPoints)) {
            return $object;
        }

        $proxy =  $this->proxyGenerator->generateProxy($object, $joinPoints);
        $proxy->setAspect($this);

        return $proxy;
    }
}
