<?php
namespace TatamiModule;
use Symfony\Component\Validator\Constraints as Assert;
class DashboardPresenter extends \Tatami\Modules\ModulePresenter
{
    public function renderDefault()
    {
        $widgetManager = new \Tatami\Widgets\WidgetManager();
        $this->eventManager->fireEvent(\Tatami\Events\Event::DASHBOARD_LOAD, $widgetManager);
        $widgets = $widgetManager->getWidgets();
        $this->template->widgets = $widgets;
        foreach($widgets as $widgetName => $widget)
        {
            $this->addComponent($widget, $widgetName);
        }
	$factory = \Symfony\Component\Validator\ValidatorFactory::buildDefault();
	$validator = $factory->getValidator();
	//$nb = new \Symfony\Component\Validator\Constraints\NotBlank;
	$test = new Test;
	
	$validator->validate($test);
    }
}

class Test
{
    /**
     * @Assert\NotBlank()
     */
    public $name;
}