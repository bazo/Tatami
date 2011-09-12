<?php
namespace Tatami\Modules;
/**
 * Description of MessagesModule
 *
 * @author Martin
 */
final class TatamiModule extends CoreModule implements IEssentialModule
{
    protected
        $name = 'Tatami',
	    
        $entryPoint = ':Tatami:dashboard:default',
	    
        $widgetName = 'Tatami\Modules\MessagesModule\MessagesWidget',
	    
        $navigation = array(
	    'Tatami' => array(
		':tatami:dashboard:',
		'Modules' => ':tatami:modules:',
		'Users' => ':tatami:users:',
                'Permissions' => ':tatami:permissions:'
	    )
        ),
	    
	$toolbar = array(
	    'users' => array(
			array(
			    'label' => 'Create new user',
			    'destination' => ':tatmi:users:',
			    'icon' => 'user-new',
			    'ajax' => true
			)
	    ),
	    'modules' => array(
		array(
		    'label' => 'Install module from internet',
		    'destination' => ':tatami:modules:browseModules',
		    'icon' => 'download',
		    'ajax' => true
		)
	    )
	),
            
        $permissions = array(
            'user' => array(
                'view' => 'View user',
                'edit' => 'Edit user',
                'add' => 'Add user',
                'delete' => 'Delete user'
            )
        )
    ;
    
    public function loadRoutes(\Nette\Application\IRouter $router, $args)
    {
	/*
	$router[] = $backRouter = new \Nette\Application\Routers\RouteList('Messages');
	$backRouter[] = new \Nette\Application\Routers\Route('admin/tatami/<presenter>[/<action>][/<id>]', array(
	    'action' => 'default',
	    'id' => NULL
	));
*/
    }
}