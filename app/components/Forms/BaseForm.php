<?php
/**
 * BaseForm
 * @author Martin Bazik
 */
namespace Tatami\Forms;

class BaseForm extends \Nette\Application\UI\Form
{
    /**
     * Adds button used to submit form.
     * @param  string  control name
     * @param  string  caption
     * @return AdvancedSubmitButton
     */
    public function addAdvSubmit($name, $caption, $icon = 'save')
    {
	return $this[$name] = new AdvancedSubmitButton($caption, $icon);
    }
}
