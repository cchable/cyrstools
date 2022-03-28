<?php
/**
 * @package   : module/Application/src/Service/Helper/NavManagerFactory.php
 *
 * @purpose   : This is the factory class for NavManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018, 21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\NavManager;


/*
 * 
 */
class NavManagerFactory
{
  /**
   * This method creates the NavManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
    $viewHelperManager = $container->get('ViewHelperManager');
    $urlHelper         = $viewHelperManager->get('url');
    
    return new NavManager($urlHelper);
  }
}
