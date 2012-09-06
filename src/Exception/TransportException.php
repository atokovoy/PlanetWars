<?php
namespace Exception;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class TransportException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
