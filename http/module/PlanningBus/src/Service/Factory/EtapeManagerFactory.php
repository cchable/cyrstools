<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/EtapeManagerFactory.php
 *
 * @purpose   : This is the factory class for EtapeManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Service\EtapeManager;
use PlanningBus\Model\EtapeTable;


/*
 * 
 */
class EtapeManagerFactory implements FactoryInterface
{

  /*
   * This method creates the EtapeManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $etapeTable   = $container->get(EtapeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new EtapeManager($etapeTable, $viewRenderer, $config);
  }
}