<?php
namespace Gridder;
use Doctrine\ORM\EntityRepository;
use Nette\Application\UI\Control;
/**
 * Description of EntityBrowser
 *
 * @author Martin
 */
class Gridder extends Control
{
    private
	/** @var Sources\IDataSource */
	$dataSource,
	    
	$columns = array()
    ;
    
    public function setDataSource(Sources\IDataSource $dataSource)
    {
	$this->dataSource = $dataSource;
    }
    
    public function bindRepository(EntityRepository $entityRepository)
    {
	$this->repository = $entityRepository;
    }
    
    public function addColumn($columnName)
    {
	$this->columns[$columnName] = $columnName;
    }
    
    public function render()
    {
	$this->template->setFile(__DIR__.'/template.latte');
	$this->template->columns = $this->columns;
	
	//$builder = $this->repository->createQueryBuilder('entity');
	//$iterableResult = $builder->getQuery()->iterate();
	//$this->template->em = $builder->getEntityManager();
	
	
	$rows = $this->dataSource->getResults();
	
	
	$this->template->rows = $rows;
	$this->template->render();
    }
}