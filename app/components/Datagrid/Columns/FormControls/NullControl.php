<?php
namespace Tatami\Components\Datagrid\Forms\Controls;

/**
 * Description of NullControl
 *
 * @author Martin
 */
class NullControl extends FormControl
{
    /**
     * @param  string  caption
     */
    public function __construct($caption = NULL)
    {
	    $this->monitor('Form');
	    parent::__construct();
	    $this->control = Html::el('span')->add(_('No filter'));
	    $this->label = Html::el('label');
    }
}