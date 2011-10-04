<?php
namespace TatamiModule;

class DashboardPresenter extends BasePresenter
{
    public function renderDefault()
    {
        $widgetManager = $this->context->getService('widgetsManager');
        //$this->eventManager->fireEvent(\Tatami\Events\Event::DASHBOARD_LOAD, $widgetManager);
        $widgets = $widgetManager->getActiveWidgets();
        $this->template->widgets = $widgets;
	
	$this->template->inactiveWidgets = $widgetManager->getInactiveWidgets();
	
        foreach($widgets as $widget)
        {
            $this->addComponent(new $widget->class, $widget->name);
        }
    }
    
    public function handleWidgetDropped($widgetName)
    {
	//var_dump($widgetName);exit;
	$widget = $this->em->getRepository('Widget')->findOneBy(array('name' => $widgetName));
	$widget->setActive(true);
	$this->em->persist($widget);
	$this->em->flush();
	$this->invalidateControl('widgets');
    }
    
    public function handleWidgetDisabled($widgetName)
    {
	//var_dump($widgetName);exit;
	$widget = $this->em->getRepository('Widget')->findOneBy(array('name' => $widgetName));
	$widget->setActive(false);
	$this->em->persist($widget);
	$this->em->flush();
	$this->invalidateControl('availableWidgets');
    }
}