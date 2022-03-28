<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/PlanningControllerFactory.php
 *
 * @purpose   : This is the factory for PlanningController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\PlanningController;
use PlanningBus\Service\PlanningManager;
use PlanningBus\Model\PlanningTable;
use PlanningBus\Model\PlanningFullTable;


/*
 * 
 */
class PlanningControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $planningTable     = $container->get(PlanningTable::class);
    $planningFullTable = $container->get(PlanningFullTable::class);
    $planningManager   = $container->get(PlanningManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('PlanningSessionContainer');
    // Instantiate the controller and inject dependencies
    return new PlanningController($planningTable, $planningFullTable, $planningManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}