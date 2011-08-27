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
    Nette\Application\Routers\Route
;
// Load Nette Framework
$params['libsDir'] = __DIR__ . '/../libs';
require $params['libsDir'] . '/Nette/loader.php';

$params['logDir'] = __DIR__ . '/../log';
Debugger::$logDirectory = $params['logDir'];
Debugger::$strictMode = TRUE;
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

$application = $configurator->container->application;
$application->errorPresenter = 'Error';
//$application->catchExceptions = TRUE;
$application->onStartup[] = function(Nette\Application\Application $application) use($configurator)
{
    $router = $application->getRouter();
    $router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
    $router[] = new Route('admin/<module>/<presenter>/<action>[/<id>]',
	    array(
		'module' => 'tatami',
		'presenter' => 'dashboard',
		'action' => 'default',
		'id' => null
	    ));
    
    $eventManager = $configurator->getContainer()->getService('EventManager');
    $params = $configurator->getContainer()->params;
    if(isset($params['installed']) and $params['installed'] == true)
    {
	$moduleManager = $configurator->getContainer()->getService('ModuleManager');
	$eventManager->addSubscriber(Tatami\Events\Event::APPLICATION_STARTUP, $moduleManager);
    }
    $eventManager->fireEvent(Tatami\Events\Event::APPLICATION_STARTUP, $application, $configurator);
};

$application->onShutdown[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('EventManager')->fireEvent(Tatami\Events\Event::APPLICATION_SHUTDOWN, $application);
};

$application->onError[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('EventManager')->fireEvent(Tatami\Events\Event::APPLICATION_ERROR, $application);
};

$application->onRequest[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('EventManager')->fireEvent(Tatami\Events\Event::APPLICATION_REQUEST, $application);
};

$application->onResponse[] = function(Nette\Application\Application $application) use($configurator)
{
    $configurator->getContainer()->getService('EventManager')->fireEvent(Tatami\Events\Event::APPLICATION_RESPONSE, $application);
};
$application->run();