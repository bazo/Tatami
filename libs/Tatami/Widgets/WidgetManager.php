<?php
namespace Tatami\Widgets;
/**
 * Description of WidgetManager
 *
 * @author Martin
 */
class WidgetManager 
{
    private
        /** @var array */
        $widgets = array()
    ;
    
    /**
     *
     * @param IWidget $widget 
     */
    public function addWidget(IWidget $widget)
    {
       $this->widgets[$widget->getReflection()->getShortName()] = $widget; 
    }
    
    /**
     *
     * @return array
     */
    public function getWidgets()
    {
        return $this->widgets;
    }
}