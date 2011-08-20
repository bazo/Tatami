<?php
/* 
 * 
 * 
 */

/**
 * Description of TextColumn
 *
 * @author Martin
 */
class TextColumn extends BaseColumn
{
    protected function  formatValue($value)
    {
        if( String::length($value) > 30 )
        {
            $newValue =  String::truncate($value, 30, '...');
            return Html::el('span')->setText($newValue)->title($value);
        }
        return $value;
    }
}
?>
