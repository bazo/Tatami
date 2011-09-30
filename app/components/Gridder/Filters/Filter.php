<?php
namespace Gridder\Filters;
use \Nette\Application\UI\Control, \Nette\Forms\Controls;
use Nette\Forms\Controls\TextInput;
/**
 * BaseFilter
 *
 * @author Martin Bažík
 * @package Core
 */
abstract class Filter extends Control implements IFilter
{
    protected
	$operator = 'like'
    ;
    
    const 
	LIKE = 'like',
	EQUAL = '='
    ;


    public function setOperator($operator)
    {
	$this->operator = $operator;
    }
    
    public function render()
    {
        return $this->name;
    }

    public function getFormControl()
    {
        return new TextInput($this->name);
    }

    public function apply(&$dql, $value)
    {
        $dql->where($this->name.' like %?%', $value);
    }

    public function getFilter(&$value)
    {
        return new FilterObject($this->parent->name, $this->operator, $value, '%'.$value.'%');
    }


}