<?php
namespace Gridder\Filters;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Controls\SelectBox;
/**
 * ArrayFilter
 *
 * @author Martin Bažík
 */
class ArrayFilter extends Filter
{
    private
	$filterField,
	$items
    ;
    
    public function  __construct(IContainer $parent, $name, $items, $filterField = null)
    {
        parent::__construct($parent, $name);
        $this->items = array('*' => '*') + $items;
	if($filterField == null)
	{
	    $this->filterField = $name;
	}
	else
	{
	    $this->filterField = $filterField;
	}
    }
    
    public function getFormControl()
    {
        return new SelectBox($this->name, $this->items);
    }
    
    public function getFilter(&$value)
    {
        if(is_numeric($value)) return new FilterObject($this->filterField, self::EQUAL, (int)$value, (int)$value, '*');
        return new FilterObject($this->filterField, self::EQUAL, $value, $value, '*');
    }
}