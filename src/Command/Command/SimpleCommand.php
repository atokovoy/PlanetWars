<?php
namespace Command\Command;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class SimpleCommand implements \Command\CommandInterface
{
    public function __construct(array $body = array())
    {
        $this->setBody($body);
    }

    public function getCommandName()
    {
        $className = get_called_class();
        $className = explode('\\', $className);

        return $className[count($className) - 1];
    }

    public function setBody(array $body)
    {
        foreach ($body as $key => $val) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($val);
            } else {
                throw new \Exception(sprintf('Unknown method %s', $method));
            }
        }
    }

    public function getBody()
    {
        $body = array();
        $vars = get_object_vars($this);
        foreach ($vars as $key => $val) {
            if ($key[0] == '_') {
                continue;
            }
            $method = 'get' . ucfirst($key);
            if (method_exists($this, $method)) {
                $body[$key] = $this->$method();
            }
        }

        return $body;
    }

    public function getHeader()
    {
        return get_called_class();
    }

    public function toString()
    {
        return serialize(array($this->getHeader(), $this->getBody()));
    }

    public function execute(\Player $player, \Registry $registry)
    {

    }

    /**
     * @static
     * @param $string
     * @return \Command\CommandInterface
     */
    public static function create($string)
    {
        list($header, $body) = unserialize($string);
        $command = new $header($body);

        return $command;
    }
}
