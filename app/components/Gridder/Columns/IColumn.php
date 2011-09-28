<?php
namespace Gridder\Columns;
/**
 *
 * @author Martin
 */
interface IColumn 
{
    public function setAlias($alias);
    public function setFilter($type);
}