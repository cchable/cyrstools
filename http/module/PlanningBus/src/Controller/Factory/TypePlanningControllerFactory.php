<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/TypePlanningControllerFactory.php
 *
 * @purpose   : This is the factory for TypePlanningController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\TypePlanningController;
use PlanningBus\Service\TypePlanningManager;
use PlanningBus\Model\TypePlanningTable;


/*
 * 
 */
class TypePlanningControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $typePlanningTable   = $container->get(TypePlanningTable::class);
    $typePlanningManager = $container->get(TypePlanningManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('TypePlanningSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new TypePlanningController($typePlanningTable, $typePlanningManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}