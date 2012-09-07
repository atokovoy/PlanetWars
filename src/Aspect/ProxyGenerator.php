<?php
namespace Aspect;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class ProxyGenerator
{
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    protected function getProxyName($fqcn)
    {
        $className = str_replace('\\', '_', $fqcn);
        return 'Proxy_' . $className;
    }

    protected function getProxyFilename($proxyName)
    {
        return $this->cacheDir . \DIRECTORY_SEPARATOR . $proxyName . '.php';
    }

    protected function getParameterDeclaration(\ReflectionParameter $param)
    {
        $declaration = '';
        if ($param->getClass() != '') {
            $declaration.= $param->getClass() .' ';
        }
        if ($param->isArray()) {
            $declaration.= 'array ';
        }
        if ($param->isPassedByReference()) {
            $declaration.= '&';
        }
        $declaration.= '$' . $param->getName();
        if ($param->isOptional()) {
            $defVal = $param->getDefaultValue();
            if (is_array($defVal)) {
                $declaration.= ' = array()';
            } else {
                $declaration.= ' = ' . $defVal;
            }
        }

        return $declaration;
    }

    protected function getMethodDeclaration($className, \ReflectionMethod $method, array $joinPoints = array())
    {
        $name = $method->getName();
        $parameters = $method->getParameters();

        $paramDef = array();
        foreach ($parameters as $param) {
            $paramDef[] = $this->getParameterDeclaration($param);
        }
        $body = '$args = func_get_args();'."\n";
        $body.= sprintf('$result = call_user_func_array(array($this->obj, "%s"), $args);', $name) . "\n";

        if (in_array($name, $joinPoints)) {
            $body.= sprintf('$this->getAspect()->callAdvice("%s", "%s", array_merge(array($this->obj), $args));', $className, $name) . "\n";
        }

        $body.= 'return $result;';

        return sprintf("public function %s(%s){\n%s\n}", $name, implode(', ', $paramDef), $body);
    }

    protected function createProxyDeclarationTemplate($methodsDeclaration)
    {
        $declaration = '<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class %s extends %s
{
    private $aspect;

    private $obj;

    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    public function setAspect($aspect)
    {
        $this->aspect = $aspect;
    }

    public function getAspect()
    {
        return $this->aspect;
    }

';
        return $declaration . implode("\n\n", $methodsDeclaration). "}\n";
    }

    /**
     * @param $obj
     * @param array $joinPoints
     * @return AspectAwareInterface
     */
    public function generateProxy($obj, array $joinPoints = array())
    {
        $fqcn = get_class($obj);
        $proxyName = $this->getProxyName($fqcn);
        $proxyFilename = $this->getProxyFilename($proxyName);
        if (false == file_exists($proxyFilename)) {

            $reflection = new \ReflectionClass($obj);
            $declarations = array();
            foreach ($reflection->getMethods() as $method) {
                /**
                 * @var $method \ReflectionMethod
                 */
                if ((!$method->isPublic()) || $method->isStatic() || $method->isConstructor()) {
                    continue;
                }

                $declarations[] = $this->getMethodDeclaration($fqcn, $method, $joinPoints) ."\n\n";
            }
            $template = $this->createProxyDeclarationTemplate($declarations);
            file_put_contents($proxyFilename, sprintf($template, $proxyName, $fqcn));
        }

        include_once($proxyFilename);

        $proxy = new $proxyName($obj);
        return $proxy;
    }
}
