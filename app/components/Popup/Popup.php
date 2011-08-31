<?php
namespace Tatami\Components;

class Popup extends \Nette\Application\UI\Control
{
    private
        /** @var \Nette\DI\IContainer */
        $context
    ;
    
    public function setContext($context)
    {
        $this->context = $context;
    }
    
    public function render()
    {
        \Nette\Diagnostics\Debugger::fireLog(__METHOD__);
        $session = $this->context->getService('session')->getSection('popup');
        $this->invalidateControl();
        echo $session->html;
        //$this->template->html =  $session->html;
        unset($session['html']);
        //$this->template->setFile(__DIR__. '/popup.latte');
        //$this->template->render();
        
    }
}