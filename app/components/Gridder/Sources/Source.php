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
	$primaryKey,
	$filterChainMode = 'and',
	$supportSFiltering = false,
	$supportsSorting = false
    ;
    
    const
	CHAIN_MODE_AND = 'and',
	CHAIN_MODE_OR = 'or'
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
    
    public function setFilterChainMode($filterChainMode)
    {
	$this->filterChainMode = $filterChainMode;
	return $this;
    }

    public function getResults() {}
    public function getTotalCount() {}
    public function limit($offset, $limit) {}
    public function getRecordsByIds($ids) {}
    public function applyFilters($filters) {}
    
    public function supportsFiltering()
    {
	return $this->supportSFiltering;
    }
    
    public function supportsSorting()
    {
	return $this->supportsSorting;
    }
}