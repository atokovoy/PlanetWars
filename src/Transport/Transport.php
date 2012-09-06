<?php
namespace Transport;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
interface Transport
{
    public function send($data);
    public function receive();
    public function hasData();
}
