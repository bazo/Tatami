<?php
include 'bootstrap.php';
use YQL\YQL;
/**
 * Description of YQLTest
 *
 * @author Martin
 */
class YQLTest extends PHPUnit_Framework_TestCase 
{
    public function testYQL()
    {
	//$query = YQL::select('*')->from('geo.countriese')->execute();
	$query = 'select * from movies.kids-in-mind';
	/*
	$yql = new YQL();
	
	$result = $yql->execute($query);
	*/
	$result = YQL::query($query);
	var_dump($result, $result->count);
	exit;
    }
}