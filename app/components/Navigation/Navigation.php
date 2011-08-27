<?php
namespace Tatami\Components\Navigation;
/**
 * Description of Navigation
 *
 * @author Martin
 */
class Navigation extends \Nette\Application\UI\Control
{
    private
        $items = array(),
	$currentModule
    ;

    public function addItem(Navigation $item)
    {
	$this->items[] = $item;
    }
    
    public function getItems()
    {
	return $this->items;
    }
    
    public function build($navigationArray)
    {
	foreach($navigationArray as $module => $items)
	{
	    foreach ($items as $label => $destination)
	    {
		if(is_array($destination))
		{
		    $children = array_slice($destination, 1);
		    $destination = $destination[0];
		    $item = new NavigationItem($label, $destination, $module);
		    foreach($children as $childLabel => $childDestination)
		    {
			$child = new NavigationItem($childLabel, $childDestination, $module);
			$item->addChild($child);
		    }
		}
		else
		{
		    $item = new NavigationItem($label, $destination, $module);
		}
		$this->items[] = $item;
	    }
	}
    }

    public function setCurrentModule($moduleName)
    {
	$this->currentModule = $moduleName;
    }

    public function render()
    {
	$this->template->setFile(__DIR__ . '/navigation.latte');
        $this->template->items = $this->items;
	$this->template->currentModule = $this->currentModule;
        $this->template->render();
    }
}