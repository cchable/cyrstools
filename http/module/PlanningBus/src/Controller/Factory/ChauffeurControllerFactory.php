<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/ChauffeurControllerFactory.php
 *
 * @purpose   : This is the factory for ChauffeurController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\ChauffeurController;
use PlanningBus\Service\ChauffeurManager;
use PlanningBus\Model\ChauffeurTable;
use PlanningBus\Model\ChauffeurFullTable;


/*
 * 
 */
class ChauffeurControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $chauffeurTable     = $container->get(ChauffeurTable::class);
    $chauffeurFullTable = $container->get(ChauffeurFullTable::class);
    $chauffeurManager   = $container->get(ChauffeurManager::class);
    
    $config             = $container->get('Config');
    $defaultRowPerPage  = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage     = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer   = $container->get('ChauffeurSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new ChauffeurController($chauffeurTable, $chauffeurFullTable, $chauffeurManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}