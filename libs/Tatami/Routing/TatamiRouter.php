<?php
namespace Tatami\Routing;
use Nette;
use Nette\Utils\Strings;
use Nette\Application\Routers\RouteList;
/**
 * Description of TatamiRouter
 *
 * @author Martin
 */
class TatamiRouter extends Nette\Object implements Nette\Application\IRouter
{
    private
	/** @var RouteList */
	$adminRouter, 
	    
	/** @var RouteList */
	$frontRouter
    ;
    
    /** @var array */
    private $cachedRoutes;
    
    public function __construct()
    {
	$this->adminRouter = new RouteList;
	$this->frontRouter = new RouteList;
    }

    public function getAdminRouter() 
    {
	return $this->adminRouter;
    }

    public function setAdminRouter($adminRouter) 
    {
	$this->adminRouter = $adminRouter;
	return $this;
    }

    public function getFrontRouter() 
    {
	return $this->frontRouter;
    }

    public function setFrontRouter($frontRouter) 
    {
	$this->frontRouter = $frontRouter;
	return $this;
    }

        
    /**
     * Maps HTTP request to a Request object.
     * @param  Nette\Http\IRequest
     * @return Nette\Application\Request|NULL
     */
    public function match(Nette\Http\IRequest $httpRequest)
    {
	if(Strings::startsWith($httpRequest->getUrl()->getPath(), '/admin'))
	    $router = $this->adminRouter;
	else $router = $this->frontRouter;
	foreach ($router as $route) {
		$appRequest = $route->match($httpRequest);
		if ($appRequest !== NULL) {
			$appRequest->setPresenterName($appRequest->getPresenterName());
			return $appRequest;
		}
	}
	return NULL;
    }
    
    private function formatPresenterClass($name)
    {
	$parts = explode(':', $name);
	$maxIndex = count($parts) - 1;
	$presenterName = $parts[$maxIndex].'Presenter';
	unset($parts[$maxIndex]);
	$module = '';
	foreach ($parts as $part)
	{
	    $module .= $part.'Module\\';
	}
	return  $module.$presenterName;
    }


    /**
     * Constructs absolute URL from Request object.
     * @param  Nette\Application\Request
     * @param  Nette\Http\Url
     * @return string|NULL
     */
    public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
    {
	$presenterName = $appRequest->getPresenterName();
	$presenterClass = $this->formatPresenterClass($presenterName);
	if(in_array('Tatami\Presenters\IFrontendModulePresenter', class_implements($presenterClass)))
	    $router = $this->frontRouter;
	else
	    $router = $this->adminRouter;
	
	if ($this->cachedRoutes === NULL) {
	    $routes = array();
	    $routes['*'] = array();

	    foreach ($router as $route) {
		$presenter = $route instanceof Route ? $route->getTargetPresenter() : NULL;

		if ($presenter === FALSE) {
			continue;
		}

		if (is_string($presenter)) {
		    $presenter = strtolower($presenter);
		    if (!isset($routes[$presenter])) {
			    $routes[$presenter] = $routes['*'];
		    }
		    $routes[$presenter][] = $route;

		} else {
		    foreach ($routes as $id => $foo) {
			    $routes[$id][] = $route;
		    }
		}
	    }

	    $this->cachedRoutes = $routes;
	}
	
	$presenter = strtolower($appRequest->getPresenterName());
	if (!isset($this->cachedRoutes[$presenter])) {
		$presenter = '*';
	}

	foreach ($this->cachedRoutes[$presenter] as $route) {
		$url = $route->constructUrl($appRequest, $refUrl);
		if ($url !== NULL) {
			return $url;
		}
	}
	return NULL;
    }
}