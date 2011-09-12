<?php
/**
 * BaseForm
 * @author Martin Bazik
 */
namespace Tatami\Forms;
use Nette\Forms\Form;
use Tatami\Forms\Controls\Html5Input as TextInput;
class BaseForm extends \Nette\Application\UI\Form
{
    public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) 
    {
	parent::__construct($parent, $name);
	$translator = $parent->getPresenter()->getService('translator');
	$this->setTranslator($translator);
    }

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
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addSearch($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'search';
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addTel($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'tel';
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addUrl($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'url';
	$input->addCondition(Form::FILLED)->addRule(Form::URL, '%label must be a valid url');
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addEmail($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'email';
	$input->addCondition(Form::FILLED)->addRule(Form::EMAIL, '%label must be a valid email');
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addDateTime($name, $label = null, $min = null, $max = null)
    {
	$input = new TextInput($label);
	$input->type = 'datetime';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addDate($name, $label = null, $min = null, $max = null)
    {
	$input = new TextInput($label);
	$input->type = 'date';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addMonth($name, $label = null, $min = null, $max = null)
    {
	$input = new TextInput($label);
	$input->type = 'month';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addWeek($name, $label = null, $min = null, $max = null)
    {
	$input = new TextInput($label);
	$input->type = 'week';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addTime($name, $label = null, $min = null, $max = null)
    {
	$input = new TextInput($label);
	$input->type = 'time';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addDateTimeLocal($name, $label = null, $min = null, $max = null)
    {
	$input = new TextInput($label);
	$input->type = 'datetime-local';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addNumber($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'number';
	$input->addCondition(Form::FILLED)->addRule(Form::FLOAT, '%label must be a number');
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addInteger($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'number';
	$input->addCondition(Form::FILLED)->addRule(Form::NUMERIC, '%label must be a number');
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addRange($name, $label = null, $min = null, $max = null, $step = 'any')
    {
	$input = new TextInput($label);
	$input->type = 'range';
	$input->getControlPrototype()->min = $min;
	$input->getControlPrototype()->max = $max;
	$input->getControlPrototype()->step = $step;
	$input->addCondition(Form::FILLED)
		->addRule(Form::FLOAT)
		->addRule(Form::RANGE, '%label must be a number between %d and %d', array($min, $max));
	return $this[$name] = $input;
    }
    
    /**
     *
     * @param string $name
     * @param string $label
     * @return Html5Input
     */
    public function addColor($name, $label = null)
    {
	$input = new TextInput($label);
	$input->type = 'search';
	$input->addCondition(Form::FILLED)
		->addRule(Form::PATTERN, '%label must be a valid hex color in format #aaaaaa', '#[0-9a-fA-F]{6}');
	return $this[$name] = $input;
    }
}
