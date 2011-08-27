<?php
namespace Tatami\Components;

class Toolbar extends \Nette\Application\UI\Control
{
    private
	$items = array()
    ;
    
    public function build($toolbarArray)
    {
	$this->items = $toolbarArray;
    }
    
    public function render()
    {
	$this->template->setFile(__DIR__.'/toolbar.latte');
	$this->template->items = $this->items;
	$this->template->render();
    }
}