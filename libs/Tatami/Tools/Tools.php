<?php
/**
 * Tools
 *
 * @author Martin Bažík
 * @package Core
 */
namespace Tatami;

class Tools
{
    public static function classExtends($class, $parentClass)
    {
        $parents = \class_parents($class);
        if(\in_array($parentClass, $parents)) return true;
        return false;
    }
}