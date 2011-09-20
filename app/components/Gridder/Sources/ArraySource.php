<?php
namespace Gridder\Sources;
/**
 * Description of ArraySource
 *
 * @author Martin
 */
class ArraySource implements IDataSource
{
    
    private 
	$iterator
    ;


    private function makeObjectArray($array)
    {
	foreach ($array as $key => $row)
	{
	    if(!is_object($row)) $array[$key] = (object)$row;
	}
	return $array;
    }
    

    public function __construct($array)
    {
	$array = $this->makeObjectArray($array);
	$this->iterator = new \ArrayObject($array);
    }
    
    public function getResults()
    {
	return $this->iterator->getIterator();
    }
}