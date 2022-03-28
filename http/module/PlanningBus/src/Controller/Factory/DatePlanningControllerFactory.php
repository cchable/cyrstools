<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/DatePlanningControllerFactory.php
 *
 * @purpose   : This is the factory for DatePlanningController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\DatePlanningController;
use PlanningBus\Service\DatePlanningManager;
use PlanningBus\Model\DatePlanningTable;


/*
 * 
 */
class DatePlanningControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $datePlanningTable   = $container->get(DatePlanningTable::class);
    $datePlanningManager = $container->get(DatePlanningManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('DatePlanningSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new DatePlanningController($datePlanningTable, $datePlanningManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}