<?php
/**
 * @package   : module/PlanningBus/src/Module.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport;

use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Validator\AbstractValidator;
use Laminas\Session\SessionManager;


/*
 *
 */
class Module implements ConfigProviderInterface
{
	
	//
	public function getConfig() : array
	{
		
		return include __DIR__ . '/../config/module.config.php';
	}
    
	/**
   * This method is called once the MVC bootstrapping is complete. 
   */
  public function onBootstrap(MvcEvent $event)
  {
    
    $application    = $event->getApplication();
    $serviceManager = $application->getServiceManager();
    $eventManager   = $event->getApplication()->getEventManager();
    
    $moduleRouteListener = new ModuleRouteListener();
    $moduleRouteListener->attach($eventManager);
    
    // The following line instantiates the SessionManager and automatically
    // makes the SessionManager the 'default' one.
    $sessionManager = $serviceManager->get(SessionManager::class);
  }  
}