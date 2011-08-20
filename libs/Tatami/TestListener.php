<?php
/**
 * Description of TestListener
 *
 * @author Martin
 */
class TestListener implements Tatami\Events\IListener
{
    public static function listensToEvent($eventName)
    {
        return true;
    }

    public static function reactToEvent($eventName, &$dispatcher, $args)
    {
    }
}