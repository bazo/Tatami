<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZipTest
 *
 * @author Martin
 */

include 'bootstrap.php';

use \Tatami\Tools\Zip;

class ZipTest extends PHPUnit_Framework_TestCase
{

    public function testZipOpen()
    {
	$zip = Zip::open('test.zip');
	$this->assertInstanceOf('\Tatami\Tools\Zip', $zip, 'Zip should be an instance of zip');
    }
    
    /**
     * @expectedException Tatami\Tools\ZipException
     */
    public function testFailsOnEmptyFiles()
    {
	$zip = Zip::open('test.zip')->archive();
    }
    
    public function testAddFile()
    {
	$zip = Zip::open('test.zip');
	$zip->addFile('zip/test.txt');
	$this->assertAttributeNotEmpty('files', $zip);
    }
    
    public function testAddFolder()
    {
	$zip = Zip::open('test.zip');
	$zip->addFolder('zip');
	$this->assertAttributeNotEmpty('files', $zip);
    }
    
    /**
     * @expectedException Tatami\Tools\ZipException
     */
    public function testAddFileNonexist()
    {
	$zip = Zip::open('test.zip');
	$zip->addFile('zip/test-not.txt');
    }
    
    /**
     * @expectedException Tatami\Tools\ZipException
     */
    public function testAddFolderNonexist()
    {
	$zip = Zip::open('test.zip');
	$zip->addFolder('zip-not');
    }
    
    public function testArchive()
    {
	$zip = Zip::open('zip/archived/test.zip')
	    ->addFolder('zip')
		->archive();
    }
    
    public function testExtract()
    {
	$zip = Zip::open('zip/archived/test.zip')
		->extract('zip/extracted');
    }
    
}