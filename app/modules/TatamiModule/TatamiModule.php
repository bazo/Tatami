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
	    
	$entities = array(
	    'Entity\Module',
	    'Entity\User',
	    'Entity\PasswordRecoveryToken',
	    'Entity\Resource',
	    'Entity\Permission',
	    'Entity\UserRole',
	    'Entity\Widget'
	),    
	    
	$widgets = array(
	    'Messages' => 'Tatami\Modules\MessagesModule\MessagesWidget',
	    'Messages2' => 'Tatami\Modules\MessagesModule\MessagesWidget2',
	    'Messages3' => 'Tatami\Modules\MessagesModule\MessagesWidget3'
	),    
	    
        $navigation = array(
	    'Tatami' => array(
		':tatami:dashboard:',
		'Modules' => ':tatami:modules:',
		'Users' => ':tatami:users:',
                'User roles' => ':tatami:roles:'
	    )
        ),
	    
	$toolbar = array(
	    'all' => array(
			array(
			    'label' => 'Users',
			    'destination' => ':tatami:users:',
			    'icon' => 'users',
			    'ajax' => false
			),
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