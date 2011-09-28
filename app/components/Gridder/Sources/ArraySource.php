<?php
namespace Gridder\Sources;
/**
 * Description of ArraySource
 *
 * @author Martin
 */
class ArraySource extends Source
{
    
    private 
	$totalCount,
	$iterator,
	$array
    ;

    public function __construct($array)
    {
	$this->array = $array;
	$this->totalCount = count($this->array);
    }
    
    private function makeObjectArray($array)
    {
	foreach ($array as $key => $row)
	{
	    if(!is_object($row)) $array[$key][0] = (object)$row;
	}
	return $array;
    }
    
    public function getTotalCount()
    {
	return $this->totalCount;
    }
    
    public function limit($offset, $limit)
    {
	$this->array = array_slice($this->array, $offset, $limit, $preserve_keys = true);
	return $this;
    }
    
    public function getResults()
    {
	$array = $this->makeObjectArray($this->array);
	$this->iterator = new \ArrayObject($array);
	return $this->iterator->getIterator();
    }
    
    public function getRecordsByIds($ids)
    {
	$records = array();
	$pk = $this->primaryKey;
	array_walk($this->array, function($row, $index) use(&$records, $pk, $ids ) {
	    if(in_array($row[$pk], $ids))
	    {
		$records[] = $row;
	    }
	});
	return $records;
    }
    
    public function applyFilters($filters)
    {
	return $this;
    }
}