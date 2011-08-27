<?php
namespace Tatami\Events;
use
    \Nette\Environment,
    \Nette\Reflection\ClassType
;
/**
 * Description of EventManager
 *
 * @author Martin
 */
class EventManager
{

    private
        $subscribers = array()
    ;

    public function __construct()
    {
    }
    
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     *
     * @param string $eventName
     * @param \Tatami\Events\ISubscriber $subscriber
     * @return EventManager
     */
    public function addSubscriber($eventName, \Tatami\Events\ISubscriber $subscriber)
    {
        $hash = \spl_object_hash($subscriber);
        $this->subscribers[$eventName][$hash] =  $subscriber;
        return $this;
    }

    /**
     *
     * @param string $eventName
     * @param \Tatami\Events\ISubscriber $subscriber
     * @return EventManager
     */
    public function removeSubscriber($eventName, \Tatami\Events\ISubscriber $subscriber)
    {
        $hash = \spl_object_hash($subscriber);
        if(isset($this->subscribers[$eventName][$hash])) unset($this->subscribers[$eventName][$hash]);
        return $this;
    }

    /**
     *
     * @param string $eventName
     * @param object $dispatcher
     * @param array $args
     * @return EventManager
     */
    public function fireEvent($eventName, &$dispatcher = null, $args = array())
    {
	if(isset($this->subscribers[$eventName]))
	{
	    $eventSubscribers = $this->subscribers[$eventName];
	    foreach($eventSubscribers as /** @var \Tatami\Subscriber */$subscriber)
	    {
		$method = $this->formatEventMethod($eventName);
		$subscriber instanceof \Tatami\Subscriber;
		if($subscriber->reflection->hasMethod($method))
		    $subscriber->$method($dispatcher, $args);
		else
		    throw new EventException(sprintf('The subscriber %s does not have event %s', $subscriber->reflection->name, $method));
	    }
	}
        return $this;
    }
    
    private function formatEventMethod($eventName)
    {
	return 'on'.ucfirst($eventName);
    }
}