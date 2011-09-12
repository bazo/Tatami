<?php
namespace Tatami\Forms\Controls;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
/**
 * Description of Html5Input
 *
 * @author Martin
 */
class Html5Input extends TextInput
{
    private $operation;
    
    public function addRule($operation, $message = NULL, $arg = NULL)
    {
	$this->operation = $operation;
	    if ($operation === Form::FLOAT) {
		    $this->addFilter(callback(__CLASS__, 'filterFloat'));
	    }
	    return parent::addRule($operation, $message, $arg);
    }
    
    public function changeValidationMessage($message)
    {
	//var_dump($this->rules);exit;
    }
}