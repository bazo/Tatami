<?php
namespace Gridder\Filters;
/**
 * Description of FilterMapper
 *
 * @author Martin
 */
class FilterMapper
{

    private static 
	$map = array(
	    'text' => 'TextFilter',
	    'array' => 'ArrayFilter',
	    'daterange' => 'DateRangeFilter'
	)
    ;

    /**
     *
     * @param type $parent
     * @param type $type
     * @return IFilter
     */
    public static function map($parent, $type)
    {
	$filterClass = 'Gridder\Filters\\'.self::$map[$type];
	return new $filterClass($parent, 'filter');
    }
}