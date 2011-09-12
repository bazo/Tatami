<?php
/**
 * Tatami bootstrap file.
 *
 * @copyright  Copyright (c) 2011 Martin Bazik
 * @package    Tatami
 */
use 
    Nette\Diagnostics\Debugger,
    Nette\Application\Routers\SimpleRouter,
    Nette\Application\Routers\Route,
    Tatami\Events\Event
;

// Load Nette Framework
$params['libsDir'] = __DIR__ . '/../libs';
require $params['libsDir'] . '/Nette/loader.php';

$params['logDir'] = __DIR__ . '/../log';
Debugger::$logDirectory = $params['logDir'];
Debugger::$strictMode = TRUE;
//Debugger::$productionMode = false;
Debugger::enable();

$params['assetsDir'] = __DIR__ . '/assets';

$configurator = new Nette\Configurator;
$configurator->container->params += $params;
$configurator->container->params['tempDir'] = __DIR__ . '/../temp';
//check if generated config exists
try
{
    $configurator->loadConfig($params['appDir'].'/config/config.neon');
}
catch(\Nette\FileNotFoundException $e)
{
    //if not, read the default config and install application
    $configurator->loadConfig($params['appDir'].'/config/config-default.neon');
}
$router = new \Tatami\Routing\TatamiRouter;
$configurator->getContainer()->addService('router', $router);

$application = $configurator->container->application;
$application->errorPresenter = 'Error';
//$application->catchExceptions = TRUE;
$application->onStartup[] = function(Nette\Application\Application $application) use($configurator)
{
    $router = $application->getRouter();
    $router instanceof \Tatami\Routing\Router;
    $eventManager = $configurator->getContainer()->getService('eventManager');
    $params = $configurator->getContainer()->params;
    if(isset($params['installed']) and $params['installed'] == true)
    {
	$moduleManager = $configurator->getContainer()->getService('moduleManager');
	$eventManager->addSubscriber(Event::APPLICATION_STARTUP, $moduleManager);
    }
    $eventManager->fireEvent(Event::APPLICATION_STARTUP, $application, $configurator);
    
    $router->adminRouter[] = new Route('admin',
	    array(
		'module' => 'tatami',
		'presenter' => 'dashboard',
		'action' => 'default',
		'id' => null
	    ));
    
    $router->adminRouter[] = new Route('admin/<module>/<presenter>[/<action>][/<id>]',
	    array(
		'module' => 'tatami',
		'presenter' => 'dashboard',
		'action' => 'default',
		'id' => null
	    ));
    $eventManager->fireEvent(Event::ROUTES_LOAD, $router->frontRouter);
    
};

$application->onShutdown[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('eventManager')->fireEvent(Event::APPLICATION_SHUTDOWN, $application);
};

$application->onError[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('eventManager')->fireEvent(Event::APPLICATION_ERROR, $application);
};

$application->onRequest[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('eventManager')->fireEvent(Event::APPLICATION_REQUEST, $application);
};

$application->onResponse[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('eventManager')->fireEvent(Event::APPLICATION_RESPONSE, $application);
};

$application->run();