<?php
namespace Gridder\Sources;
/**
 * Description of Source
 *
 * @author Martin
 */
class Source implements IDataSource
{
    protected
	$primaryKey
    ;
    
    public function setPrimaryKey($primaryKey)
    {
	$this->primaryKey = $primaryKey;
	return $this;
    }
        
    public function getPrimaryKey()
    {
	return $this->primaryKey;
    }
    
    public function getResults() {}
    public function getTotalCount() {}
    public function limit($offset, $limit) {}
    public function getRecordsByIds($ids) {}
    public function applyFilters($filters) {}
}