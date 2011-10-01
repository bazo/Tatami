<?php
namespace Tatami\Presenters;
use Tatami\Services\MailBuilder;

abstract class TatamiPresenter extends BasePresenter
{
    protected 
	/** @var \Tatami\Services\MailBuilder */
	$mailBuilder,
	$toolbar = null
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