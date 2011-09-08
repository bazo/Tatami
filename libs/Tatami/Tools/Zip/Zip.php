<?php
namespace Tatami\Tools;

class ZipException extends \Exception{}

class Zip extends \Nette\Object
{
    private 
	$fileName,
	$zipper,
	$files = array()
    ;
    
    public static function open($fileName)
    {
	return new self($fileName);
    }
    
    public function __construct($fileName)
    {
	$this->fileName = $fileName;
	$this->files = array();
	$this->zipper = new \ZipArchive($fileName);
    }
    
    public function addFile($file)
    {
	if(file_exists($file) and is_file($file))
	    $this->files[] = $file;
	else throw new ZipException(sprintf('File %s does not exist or is not a file', $file));
	return $this;
    }
    
    public function addFolder($folder)
    {
	if(file_exists($folder) and is_dir($folder))
	{
	    $files = glob($folder.'/*');
	    foreach($files as $file)
	    {
		if(is_file($file)) $this->addFile($file);
		if(is_dir($file)) $this->addFolder($file);
	    }
	}
	else throw new ZipException(sprintf('Folder %s does not exist or is not a folder', $folder));
	return $this;
    }
    
    public function archive()
    {
	if(empty($this->files)) throw new ZipException('No files to archive. Please add files using addFile or addFolder Method');
	$this->zipper->open($this->fileName, \ZipArchive::OVERWRITE);
	foreach($this->files as $file)
	{
	    $this->zipper->addFile($file);
	}
	$this->zipper->close();
    }
    
    public function extract($destination, $entries = null)
    {
	if(is_dir($destination))
	{
	    $this->zipper->open($this->fileName);
	    $this->zipper->extractTo($destination, $entries);
	    $this->zipper->close();
	}
	else throw new ZipException(sprintf('Destination %s is not a folder', $destination));
	return $this;
    }
    
    public function getFileName()
    {
	return $this->fileName;
    }
}