<?php
/**
 * DateRangeFilter
 *
 * @author Martin Bažík
 * @package Core
 */
class DibiDatagrid_DateRangeFilter extends BaseFilter
{
    public function getFormControl()
    {
        return new multipleDateField($this->name);
    }

    public function getFilter(&$value)
    {
        $from = strtotime($value['from']);
        $to = strtotime($value['to']);
        return new DateRangeFilterObject($this->parent->name, $value, $from, $to);
    }
}