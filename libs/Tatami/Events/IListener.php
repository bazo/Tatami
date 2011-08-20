<?php
namespace Tatami\Events;
/**
 * Description of IListener
 *
 * @author Martin
 */
interface IListener
{
    /*
     * return bool
     */
    public static function listensToEvent($eventName);

    public static function reactToEvent($eventName, &$dispatcher, $args);
}