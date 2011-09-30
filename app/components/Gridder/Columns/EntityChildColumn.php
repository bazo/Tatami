<?php
namespace Gridder\Columns;
use Doctrine\ORM\PersistentCollection;
/**
 * Description of BaseColumn
 *
 * @author Martin
 */
class EntityChildColumn extends BaseColumn
{
    private 
	$path
    ;
    
    /**
     *
     * @param mixed $value
     * @param mixed $record
     * @return BaseColumn
     */
    public function setRecord($record)
    {
	$this->value = null;
	$path = explode('->', $this->path);
	$value = $record;
	foreach($path as $pathFragment)
	{
	    $value = $value->$pathFragment;
	}
	if($value instanceof PersistentCollection)
	{
	    $members = $value->getValues();
	    foreach ($members as $member)
	    {
		$this->value[] = $member->__toString();
	    }
	}
	else
	{
	    $this->value = $value->__toString();
	}
	
        $this->record = $record;
        return $this;
    }
    
    public function setPath($path)
    {
	$this->path = $path;
	return $this;
    }
    
    /**
     *
     * @param mixed $value
     * @return mixed
     */
    protected function formatValue($value)
    {
	if(is_array($value))
	{
	    return implode(', ', $value);
	}
	else return $value;
    }
}
