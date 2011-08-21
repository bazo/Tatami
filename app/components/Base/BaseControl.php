<?php
namespace Tatami\Components;
use Nette\Application\UI\Control;
/**
 * Description of BaseControl
 *
 * @author Martin
 */
class BaseControl extends Control
{
    protected function createTemplate($class = NULL)
    {
	$template = parent::createTemplate($class);
	$translator = $this->getPresenter()->getService('Translator');
	$template->setTranslator($translator);
	return $template;
    }
}