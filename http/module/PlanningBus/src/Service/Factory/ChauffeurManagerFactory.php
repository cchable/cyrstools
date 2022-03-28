<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/ChauffeurManagerFactory.php
 *
 * @purpose   : This is the factory class for ChauffeurManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Service\ChauffeurManager;
use PlanningBus\Model\ChauffeurTable;
use PlanningBus\Model\VehiculeTable;


/*
 * 
 */
class ChauffeurManagerFactory implements FactoryInterface
{

  /*
   * This method creates the ChauffeurManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $chauffeurTable = $container->get(ChauffeurTable::class);
    $vehiculeTable  = $container->get(VehiculeTable::class);
    $viewRenderer   = $container->get('ViewRenderer');
    $config         = $container->get('Config');

    return new ChauffeurManager($chauffeurTable, $vehiculeTable, $viewRenderer, $config);
  }
}