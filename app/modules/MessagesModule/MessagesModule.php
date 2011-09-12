<?php
namespace Tatami\Modules;
/**
 * Description of MessagesModule
 *
 * @author Martin
 */
class MessagesModule extends Module
{
    protected
	$name = 'Messages',
        $widgetName = 'Tatami\Modules\MessagesModule\MessagesWidget',
        $entryPoint = ':Messages:inbox:',
        $navigation = array(
            'Messages' => array(
		':messages:inbox:',
                'Inbox' => ':Messages:inbox:',
                'Sent' => ':Messages:sent:'
            )
        )
    ;
    
    function loadDashboardWidget(\Tatami\Widgets\WidgetManager &$widgetManager, $args)
    {
        $widgetManager->addWidget(new $this->widgetName());
    }
    
    public function loadRoutes(\Nette\Application\IRouter $router, $args)
    {
	/*
	$router[] = $backRouter = new \Nette\Application\Routers\RouteList('Messages');
	$backRouter[] = new \Nette\Application\Routers\Route('admin/messages/<presenter>[/<action>][/<id>]', array(
	    'action' => 'default',
	    'id' => NULL
	));
*/
    }
}