<?php
namespace Gridder\Filters;
use Nette\Forms\Controls\TextInput;
/**
 * Description of TextFilter
 *
 * @author Martin
 */
class TextFilter extends Filter
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
        return new FilterObject($this->parent->name, 'like', $value, '%'. $value.'%');
    }
}