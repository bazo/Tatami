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
	/** @var EntityRepository */
	$repository,
	    
	$columns = array()
    ;
    
    
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
	$builder = $this->repository->createQueryBuilder('entity');
	$iterableResult = $builder->getQuery()->iterate();
	$this->template->em = $builder->getEntityManager();
	$this->template->rows = $iterableResult;
	
	$this->template->render();
    }
}