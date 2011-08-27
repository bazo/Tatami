<?php
namespace Tatami\Events;
/**
 * Description of Events
 *
 * @author Martin
 */
final class Event 
{
    const
        DASHBOARD_LOAD = 'dashboardLoad',
        ROUTES_LOAD = 'routesLoad',
        PERMISSIONS_LOAD = 'permissionsLoad',
        APPLICATION_STARTUP = 'applicationStart',
        APPLICATION_SHUTDOWN = 'applicationShutdown',
        APPLICATION_ERROR = 'applicationError',
        APPLICATION_REQUEST = 'applicationRequest',
        APPLICATION_RESPONSE = 'applicationResponse',
        ENTITY_MANAGER_LOCKED = 'entityManagerLocked'
    ;
}