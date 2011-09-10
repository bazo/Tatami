<?php
namespace Tatami\Components\AssetsLoader;
use Nette\Application\UI\Control, \Tatami\Modules\ModuleManager, \Nette\Utils\Strings;
/**
 * Description of AssetsLoader
 *
 * @author Martin
 */
class AssetsLoader extends Control
{
    protected 
	$files,
	/** @var ModuleManager */
	$moduleManager,
	$webtemp,
	$tempUrl,
	$mode,
	$wwwDir,
	$basePath,
	$module
    ;
    
    const
	MODE_CSS = ':css:',
	MODE_JS = ':js:',
	MODE_ICONS = ':icons:',
	MODE_IMAGES = ':images:'
    ;
    
    public function setModuleManager(ModuleManager $moduleManager)
    {
	$this->moduleManager = $moduleManager;
	return $this;
    }
    
    public function setWebtemp($dir)
    {
	$this->webtemp = $dir;
	return $this;
    }
    
    public function setModule($moduleName)
    {
	$this->module = $moduleName;
	return $this;
    }
    
    public function setTempUrl($tempUrl) 
    {
	$this->tempUrl = $tempUrl;
	return $this;
    }

    public function setWwwDir($wwwDir) 
    {
	$this->wwwDir = $wwwDir;
	return $this;
    }

    public function setBasePath($basePath) 
    {
	$this->basePath = $basePath;
	return $this;
    }

            
    public function addFile($module, $file)
    {
	$moduleDir = dirname($this->moduleManager->getModule($module)->getFile());
	
	switch ($this->mode)
	{
	    case self::MODE_CSS:
		$assetFolder = 'css';
	    break;
	
	    case self::MODE_JS:
		$assetFolder = 'js';
	    break;
	}
	
	$this->files[$module] = $moduleDir.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.$assetFolder.DIRECTORY_SEPARATOR.$file;
	return $this;
    }
    
    
    
    private function isModule($moduleName)
    {
	return $this->moduleManager->moduleExists($moduleName);
    }
    
    public function renderCss($files)
    {
	$this->mode = self::MODE_CSS;
	$this->render(func_get_args());
    }
    
    public function renderJs($files)
    {
	$this->mode = self::MODE_JS;
	$this->render(func_get_args());
    }
    
    private function render($args)
    {
	$this->files = array();
	if(!isset($this->module))
	{
	    if($this->isModule($args[0]))
		$module = Strings::lower(array_shift($args));
	    else $module = Strings::lower ($this->parent->module->name);
	}
	else $module = $this->module;
	$files = $args;
	$moduleDir = dirname($this->moduleManager->getModule($module)->getFile());
	switch ($this->mode)
	{
	    case self::MODE_CSS:
		$renderer = new Renderers\CssRenderer($moduleDir, $files, $this->webtemp, $this->tempUrl);
		//$renderer->fileFilters[] = new Filters\CssUrlsFilter($this->wwwDir, $this->basePath);
		$renderer->filters[] = new Filters\DataUriFilter($moduleDir.DIRECTORY_SEPARATOR.'assets');
	    break;
	
	    case self::MODE_JS:
		$renderer = new Renderers\JsRenderer($moduleDir, $files, $this->webtemp, $this->tempUrl);
	    break;
	}
	
	$renderer->render();
	unset($renderer);
    }
}