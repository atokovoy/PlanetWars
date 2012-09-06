<?php
namespace Event;

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
interface Listener
{
    public function handle(Event $event);
}
