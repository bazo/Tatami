<?php
namespace Tatami\Modules;
use Tatami\Events;
/**
 *
 * @author Martin
 */
interface IModule 
{
    public function getName();

    public function getRoutes();

    public function getNavigation();

    public function getPermissions();

    public function getEntryPoint();
    
    public function getSearchResults($expression);
    
    public function getToolbar($toolbarName);
    
    public function isActive();
}