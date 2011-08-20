<?php
namespace Tatami\Components\Datagrid\Filters;
/**
 * ForeignFieldArrayFilter
 *
 * @author Martin Bažík
 */
class ForeignFieldArrayFilter extends ArrayFilter
{
    private
	$filterField
    ;
    
    public function  __construct(IComponentContainer $parent = NULL, $name = NULL, $items = array(), $filterField)
    {
        parent::__construct($parent, $name);
        $this->items = $items;
	$this->filterField = $filterField;
    }
    
    public function getFilter(&$value)
    {
        if(is_numeric($value)) return new FilterObject($this->filterField, '= %i', (int)$value, (int)$value);
        return new FilterObject($this->filterField, '= %s', $value, $value);
    }
    
    public function apply(&$dql, $value)
    {
        $dql->where($this->filterField.' like %?%', $value);
    }
}