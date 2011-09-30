<?php
namespace Gridder\Columns;
use Gridder;
use Gridder\Filters;
use Gridder\Filters\FilterMapper;
use Nette\Application\UI\Control;
use Nette\Utils\Html;
/**
 * Description of BaseColumn
 *
 * @author Martin
 */
abstract class BaseColumn extends Control implements IColumn
{
    protected 
        $record,
        $value,
        $alias,
        $hasFilter = false,
        $filterType,
        $defaultFilterType = 'text',
        $type
    ;

    public
        $onCellRender = array(),
        $hidden = false
    ;

    /**
     *
     * @param mixed $value
     * @param mixed $record
     * @return BaseColumn
     */
    public function setRecord($record)
    {
        $this->value = $record->{$this->name};
        $this->record = $record;
        return $this;
    }

    public function getType() 
    {
        return $this->type;
    }

    public function setType($type) 
    {
        $this->type = $type;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getValue()
    {
        $this->value = $this->formatValue($this->value);
        $this->onCellRender();
        return $this->value;
    }

    /**
     *
     * @param mixed $value
     * @return mixed
     */
    protected function formatValue($value)
    {
        return $value;
    }

    /**
     * changes the value of the field according to the callback
     */
    protected function onCellRender()
    {
        foreach($this->onCellRender as $function)
        {
            $this->value = $function($this->value, $this->record);
        }
    }
    
    public function renderHeader()
    {
        if($this->alias != null) return $this->alias;
        else return $this->name;
    }

    public function hasFilter()
    {
        return $this->hasFilter;
    }

    /**
     * sets DefaultFilter for the column type
     */
    public function setDefaultFilter()
    {
        $filterType = $this->filterType != null ? $this->filterType : $this->defaultFilterType;
        $this->setFilter($filterType);
    }

    /**
     *
     * @param string $type
     * @return IFilter
     */
    public function setFilter($type)
    {
        $this->hasFilter = true;
        $this->parent->hasFilters = true;
        return FilterMapper::map($this, $type);
    }

    public function getFilter()
    {
        return $this->getComponent('filter')->getFormControl();
    }

    public function disableFilter()
    {
	$this->hasFilter = false;
    }
    
    protected function beforeSetFilter()
    {
	if($this->hasFilter)
	{
		$filter = $this->getComponent('filter');
		$this->removeComponent($filter);
	}
        $this->hasFilter = true;
        $this->parent->hasFilters = true;
    }

    public function setTextFilter()
    {
        $this->beforeSetFilter();
        $this->filterType = 'text';
        return new Filters\TextFilter($this, 'filter');
    }
    
    public function setArrayFilter($items, $field = null)
    {
        $this->beforeSetFilter();
        $this->filterType = 'array';
        return new Filters\ArrayFilter($this, 'filter', $items, $field);
    }

    public function getAlias()
    {
        return $this->alias;
    }

    /**
     *
     * @param string $alias
     * @return BaseColumn
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     *
     * @param Callback $callback
     * @return BaseColumn
     */
    public function addOnCellRender(Callback $callback)
    {
        $this->onCellRender[] = $callback;
        return $this;
    }

    public function render()
    {
	
	$value = $this->getValue();
	$html = Html::el('td')->class('cell '.$this->type.' '.$this->name);
	if($value instanceof Html)
	{
	    $html->add($value);
	}

	elseif($value == null)
	{
	    $html->setText('');
	}
	else
	{
	    $html->setText($value);
	}
        echo $html;
    }

    public function filterByOtherFieldArray($field, $items)
    {
	$items = array('' => '-') + $items;    
        $this->beforeSetFilter();
        $this->filterType = 'array';
        return new Filters\ForeignFieldArrayFilter($this, 'filter', $items, $field);
    }
}
