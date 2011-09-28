<?php
namespace Gridder\Sources;
use Doctrine\ORM\EntityRepository;
use \Doctrine\ORM\QueryBuilder;
/**
 * Description of EntitySource
 *
 * @author Martin
 */
class EntitySource extends Source
{
    private
	/** @var EntityRepository */
	$repository,
	/** @var QueryBuilder */    
	$builder
    ;
    
    public function __construct(EntityRepository $repository)
    {
	$this->repository = $repository;
	$this->builder = $this->repository->createQueryBuilder('entity');
    }
    
    public function getResults()
    {
	$result =  $this->builder->getQuery()->iterate(array(), \Doctrine\ORM\Query::HYDRATE_SIMPLEOBJECT);
	$result->rewind();
	return $result;
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
	return $this;
    }
}