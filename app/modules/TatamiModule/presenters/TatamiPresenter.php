<?php
namespace TatamiModule;
use Nette\Environment;
use Tatami\Services\MailBuilder;
abstract class TatamiPresenter extends BasePresenter
{
    protected 
	/** @var \Tatami\Services\MailBuilder */
	$mailBuilder
    ;
    
    public function  startup()
    {
        parent::startup();
        if(!isset($this->context->params['installed']))
	{
	    $this->redirect(':tatami:installation:');
	}
	$this->mailBuilder = new MailBuilder($this);
    }
}