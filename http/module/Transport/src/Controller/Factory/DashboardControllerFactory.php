<?php
/**
 * @package   : module/Transport/src/Controller/Factory/DashboardControllerFactory.php
 *
 * @purpose   : This is the factory for DashboardController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\DashboardController;
//use Transport\Service\DashboardManager;
use Transport\Model\ChauffeurTable;
//use Transport\Model\MarqueTable;
//use Transport\Model\TypeVehiculeTable;


/*
 * 
 */
class DashboardControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $chauffeurTable     = $container->get(ChauffeurTable::class);
    //$marqueTable        = $container->get(MarqueTable::class);
    //$typeVehiculeTable  = $container->get(TypeVehiculeTable::class);
    //$vehiculeManager = $container->get(VehiculeManager::class);
    
    //$config            = $container->get('Config');
    //$defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    //$stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('DashboardSessionContainer');
    
    // Instantiate the controller and inject dependencies
    //return new VehiculeController($vehiculeTable, $vehiculeManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
    return new DashboardController(
      $chauffeurTable, 
      //$marqueTable,
      //s$typeVehiculeTable,
      $sessionContainer
    );
  }
}