<?php
namespace Tatami\Modules;
/**
 * Description of MessagesModule
 *
 * @author Martin
 */
final class TatamiModule extends CoreModule
{
    protected
        $name = 'Tatami',
	    
        $entryPoint = ':Tatami:dashboard:default',
	    
        $widgetName = 'Tatami\Modules\MessagesModule\MessagesWidget',
	    
        $navigation = array(
	    'Tatami' => array(
		':tatami:settings:',
		'Modules' => ':tatami:modules:',
		'Users' => ':tatami:users:'
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
	    )
	)
    ;
}