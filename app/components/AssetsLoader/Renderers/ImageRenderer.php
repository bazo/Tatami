<?php
namespace Tatami\Components\AssetsLoader\Renderers;
use Nette\Utils\Html;
use Nette\Utils\MimeTypeDetector;
/**
 * Description of CssRenderer
 *
 * @author Martin
 */
class ImageRenderer extends AssetsRenderer
{
    protected 
	$prefix = 'imageloader',
	$assetFolder = 'images',
	$file,
	$suffix
    ;

    public function __construct($moduleDir, $files, $webtemp, $tempUrl)
    {
	$this->file = $files[0];
	$this->moduleDir = $moduleDir;
	$this->sourcePath = $moduleDir.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.$this->assetFolder;
	$this->webtemp = $webtemp;
	$this->tempUrl = $tempUrl;
    }
    
    public function generate()
    {
	$pathInfo = pathinfo($this->file);
	$this->suffix = '.'.$pathInfo['extension'];
	$generatedFile = $this->getGeneratedFileName();
	$lastModified = $this->getLastModified();
	if (!file_exists($generatedFile) || $lastModified > filemtime($generatedFile)) 
	{
	    $file = $this->sourcePath.DIRECTORY_SEPARATOR.$this->file;
	    copy($file, $generatedFile);
	}
	return basename($generatedFile).'?'.filemtime($generatedFile);
    }
    
    protected function getGeneratedFileName()
    {
	return $this->webtemp.'/'.$this->prefix.'-'.$this->file.$this->suffix;
    }
    
    protected function getHtml($fileName)
    {
	return Html::el('img')->src($fileName);
    }
}