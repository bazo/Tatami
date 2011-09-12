<?php
namespace Tatami\Components\AssetsLoader\Renderers;
use Nette\Utils\SafeStream;
/**
 * Description of AssetsRenderer
 *
 * @author Martin
 */
abstract class AssetsRenderer implements IAssetRenderer 
{
    public
	$filters = array(),
	$fileFilters = array()
    ;
    
    protected 
	$files = array(),
	$content = null,
	$prefix,
	$suffix,
	$filename,
	$webtemp,
	$tempUrl,
	$sourcePath,
	$moduleDir
    ;
    
    public function __construct($moduleDir, $files, $webtemp, $tempUrl)
    {
	$this->moduleDir = $moduleDir;
	$this->sourcePath = $moduleDir.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.$this->assetFolder;
	$this->addFiles($files);
	$this->webtemp = $webtemp;
	$this->tempUrl = $tempUrl;
    }
    
    private function addFiles($files)
    {
	foreach($files as $file)
	{
	    $this->files[] = $this->sourcePath.DIRECTORY_SEPARATOR.$file;
	}
    }
    
    public function getSourcePath()
    {
	return $this->sourcePath;
    }
    
    protected function getGeneratedFileName()
    {
	return $this->webtemp.'/'.$this->prefix.'-'.md5(implode($this->files)).$this->suffix;
    }
    
    protected function getLastModified()
    {
	$lastModified = null;
	foreach($this->files as $file)
	{
	    $lastModified = max($lastModified, filemtime($file));
	}
	return $lastModified;
    }
    
    public function generate()
    {
	$generatedFile = $this->getGeneratedFileName();
	$lastModified = $this->getLastModified();
	if (!file_exists($generatedFile) || $lastModified > filemtime($generatedFile)) 
	{
	    if (!in_array(SafeStream::PROTOCOL, stream_get_wrappers())) 
	    {
		SafeStream::register();
	    }
	    $content = null;
	    foreach($this->files as $file)
	    {
		$content .= file_get_contents($file);
		foreach ($this->fileFilters as $filter) 
		{
		    $content = call_user_func($filter, $content, $this, $file);
		}
	    }
	    foreach ($this->filters as $filter) 
	    {
		$content = call_user_func($filter, $content, $this);
	    }
	    file_put_contents("safe://" . $generatedFile, $content);
	}
	return basename($generatedFile).'?'.filemtime($generatedFile);
    }
    
    abstract protected function getHtml($fileName);
    
    protected function getTempUrlFileName($tempUrl, $fileName)
    {
	return $tempUrl.'/'.$fileName;
    }
    public function render()
    {
	$fileName = $this->generate();
	echo $this->getHtml($this->getTempUrlFileName($this->tempUrl, $fileName));
    }
}