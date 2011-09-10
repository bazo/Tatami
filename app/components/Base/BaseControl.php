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
    public function templatePrepareFilters($template)
    {
        $latte = new \Nette\Latte\Engine;
        $template->registerFilter($latte);
        $set = \Nette\Latte\Macros\MacroSet::install($latte->parser);
        $set->addMacro('popup', 'Macros::popup(%node.word, $template) ');
    }
    
    protected function createTemplate($class = NULL)
    {
	$template = parent::createTemplate($class);
	$translator = $this->getPresenter()->getService('translator');
	$template->setTranslator($translator);
	return $template;
    }
    
    protected function refreshPopup()
    {
        $this->presenter->invalidateControl('popup');
    }
    
    public function handleclosePopup()
    {
        $this->presenter->invalidateControl('popup');
    }
}