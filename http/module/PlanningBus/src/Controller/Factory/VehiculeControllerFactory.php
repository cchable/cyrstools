<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/VehiculeControllerFactory.php
 *
 * @purpose   : This is the factory for VehiculeController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\VehiculeController;
use PlanningBus\Service\VehiculeManager;
use PlanningBus\Model\VehiculeTable;


/*
 * 
 */
class VehiculeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $vehiculeTable   = $container->get(VehiculeTable::class);
    $vehiculeManager = $container->get(VehiculeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('VehiculeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new VehiculeController($vehiculeTable, $vehiculeManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}