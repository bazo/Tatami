<?php
namespace TatamiModule;

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
    }
}