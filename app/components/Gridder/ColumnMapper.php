<?php
namespace Gridder;
use Gridder\Columns;
/**
 * Description of ColumnMapper
 *
 * @author Martin
 */
class ColumnMapper
{
    private $map = array(
	'integer' => 'IntegerColumn',
	'float' => 'FloatColumn',
	'string' => 'TextColumn',
	'text' => 'TextColumn',
	'array' => 'StandardColumn',
	'datetime' => 'DatetimeColumn',
	'timestamp' => 'DatetimeColumn',
	'image' => 'ImageColumn',
	'bool' => 'BoolColumn',
	'entityChild' => 'EntityChildColumn'
    );
    
    
    public function map($parent, $name, $type, $autoAddFilter)
    {
	$columnType = $this->map[$type];
	$columnClass = 'Gridder\Columns\\'.$columnType;
	$column = new $columnClass($parent, $name);
	if($autoAddFilter)
	{
	    $column->setDefaultFilter();
	}
	return $column;
    }
}