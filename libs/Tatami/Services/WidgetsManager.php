<?php
namespace Tatami\Widgets;
use Tatami\Models\Repositories\EntityRepository;
/**
 * Description of WidgetManager
 *
 * @author Martin
 */
class WidgetsManager 
{
    private
        /** @var array */
        $widgets = array(),
	    
	/** @var EntityRepository */    
	$repository
    ;
    
    public function __construct($repository)
    {
	$this->repository = $repository;
    }
    
    /**
     *
     * @param IWidget $widget 
     */
    public function addWidget(IWidget $widget)
    {
       $this->widgets[$widget->getReflection()->getShortName()] = $widget; 
    }
    
    public function getActiveWidgets()
    {
	return $this->repository->findBy(array('active' => true));
    }
    
    public function getInactiveWidgets()
    {
	return $this->repository->findBy(array('active' => false));
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