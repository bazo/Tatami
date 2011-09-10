<?php
namespace Tatami\Models\Repositories;
use Doctrine\ORM\EntityRepository as DoctrineER;
/**
 * Description of EntityRepository
 *
 * @author Martin
 */
class EntityRepository extends DoctrineER
{
    public function fetchPairs($key, $value)
    {
	$roles = $this->createQueryBuilder('entity')
		->select("entity.$key, entity.$value")
		->getQuery()
		->execute();
	$pairs = array();
	foreach($roles as $role)
	{
	    $pairs[$role[$key]] = $role[$value];
	}
	return $pairs;
    }
}