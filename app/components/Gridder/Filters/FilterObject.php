<?php
namespace Gridder\Filters;
/**
 * FilterObject
 *
 * @author Martin Bažík
 * @package Core
 */
class FilterObject
{
    protected
        $field,

        $operator,

        $value,

        $queryValue,

        $emptyValue = ''
    ;

    public function  __construct($field, $operator, $value, $queryValue, $emptyValue = '')
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
        $this->queryValue = $queryValue;
	$this->emptyValue = $emptyValue;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getEmptyValue()
    {
        return $this->emptyValue;
    }
    
    public function getOperator()
    {
	return $this->operator;
    }
    
    public function getQueryValue()
    {
	return $this->queryValue;
    }
       
    public function getField()
    {
	return $this->field;
    }

    public function isEmpty()
    {
	return $this->value == $this->emptyValue;
    }
}