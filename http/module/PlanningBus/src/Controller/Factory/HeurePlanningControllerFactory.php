<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/HeurePlanningControllerFactory.php
 *
 * @purpose   : This is the factory for HeurePlanningController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\HeurePlanningController;
use PlanningBus\Service\HeurePlanningManager;
use PlanningBus\Model\HeurePlanningTable;


/*
 * 
 */
class HeurePlanningControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $heurePlanningTable   = $container->get(HeurePlanningTable::class);
    $heurePlanningManager = $container->get(HeurePlanningManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('HeurePlanningSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new HeurePlanningController($heurePlanningTable, $heurePlanningManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}