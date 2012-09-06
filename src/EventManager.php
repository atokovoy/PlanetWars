<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class EventManager
{
    protected $listeners;

    public function notify(\Event\Event $event)
    {
        foreach ($this->listeners as $listener) {
            $listener->handle($event);
        }
    }

    public function subscribe(\Event\Listener $listener)
    {
        $this->listeners[] = $listener;
    }
}
