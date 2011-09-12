<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class TestPresenter extends \Tatami\Presenters\BasePresenter
{
    protected function createComponentFormTest($name)
    {
	$form = new \Tatami\Forms\BaseForm($this, $name);
	$form->addUrl('url', 'Url')->changeValidationMessage('test');
	$form->addEmail('email', 'email');
	$form->addNumber('number', 'Number');
	$form->addRange('range', 'range', 0, 100, 1);
	$form->addColor('color', 'color');
	$form->addDateTime('datetime', 'datetime');
	$form->addDateTimeLocal('datetimelocal', 'datetimelocal');
	$form->addTime('time', 'time');
	$form->addMonth('month', 'month');
	$form->addWeek('week', 'week');
	
	$form->addSubmit('btnSubmit');
	$form->onSuccess[] = function(\Nette\Application\UI\Form $form){
	  var_dump($form->values);  
	};
    }
}