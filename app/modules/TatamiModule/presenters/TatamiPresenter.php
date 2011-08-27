<?php
namespace TatamiModule;
use Nette\Environment;

abstract class TatamiPresenter extends BasePresenter
{

    public function  startup()
    {
        parent::startup();
        if(!isset($this->context->params['installed']))
	{
	    $this->redirect(':tatami:installation:');
	}
    }
}