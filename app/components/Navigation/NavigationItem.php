<?php
namespace Tatami\Components\Navigation;

class NavigationItem extends \Nette\Object
{
    private 
	$label,
	$destination,
	$module,
	$children = array()
    ;


    public function __construct($label, $destination, $module = null)
    {
	$this->label = $label;
	$this->destination = $destination;
	$this->module = $module;
    }
    
    public function addChild(NavigationItem $item)
    {
	$this->children[] = $item;
	return $this;
    }
    
    public function addChildren($children)
    {
	$this->children += $children;
	return $this;
    }
    
    public function hasChildren()
    {
	if(empty ($this->children)) return false;
	return true;
    }
    
    public function getChildren()
    {
	return $this->children;
    }
    
    public function getDestination()
    {
	return $this->destination;
    }
    
    public function getLabel()
    {
	return $this->label;
    }
    
    public function getModule()
    {
	return $this->module;
    }
}