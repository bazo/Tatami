<?php
/**
 * Description of TextFilter
 *
 * @author Martin
 */
class DibiDatagrid_TextFilter extends BaseFilter
{
    public function render()
    {
        return $this->name;
    }

    public function getFormControl()
    {
        $input = new TextInput($this->name);
        $input->getControlPrototype()->class = 'text-filter';
        return $input;
    }

    public function apply(&$dql, $value)
    {
        $dql->where($this->name.' like %?%', $value);
    }

    public function getFilter(&$value)
    {
        return new FilterObject($this->parent->name, 'like %s', $value, '%'. $value.'%');
    }
}