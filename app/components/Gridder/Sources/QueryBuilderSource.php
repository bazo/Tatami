<?php
namespace Gridder\Sources;
use Gridder\Exception;
use \Doctrine\ORM\QueryBuilder;
/**
 * Description of EntitySource
 *
 * @author Martin
 */
class QueryBuilderSource extends Source
{
    protected
	/** @var QueryBuilder */    
	$builder,
	$hydrationMode
    ;
    const 
	HYDRATION_SIMPLE = 'simple',
	HYDRATION_COMPLEX = 'complex'
    ;

    public function __construct(QueryBuilder $queryBuilder, $hydrationMode = self::HYDRATION_SIMPLE)
    {
	$this->hydrationMode = $hydrationMode;
	$this->builder = $queryBuilder;
    }
    
    protected function setQueryBuilder(QueryBuilder $queryBuilder)
    {
	if($queryBuilder->getType() != QueryBuilder::SELECT)
	{
	    throw new Exception('Only QueryBuilder of type QueryBuilder::SELECT is accepted');
	}
    }
    
    public function getResults()
    {
	$query =  $this->builder->getQuery();
	if($this->hydrationMode == static::HYDRATION_COMPLEX)
	{
	    $result = $query->iterate();
	}
	if($this->hydrationMode == static::HYDRATION_SIMPLE)
	{
	    $result = $query->iterate(array(), \Doctrine\ORM\Query::HYDRATE_SIMPLEOBJECT);
	}
	return new EntitySource\EntityIterator($result, $this->builder->getEntityManager());
    }
    
    public function getTotalCount()
    {
	$result = $this->builder->getQuery()->execute(array(), \Doctrine\ORM\Query::HYDRATE_ARRAY);//execute()->rowCount();
	return count($result);
    }
    
    public function limit($offset, $limit)
    {
	$this->builder->setFirstResult( $offset )
	    ->setMaxResults( $limit );
	return $this;
    }
    
    public function getRecordsByIds($ids)
    {
	
    }
    
    public function applyFilters($filters)
    {
	if($filters == null) return $this;
	return $this;
    }
}