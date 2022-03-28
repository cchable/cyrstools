<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/PlanningManagerFactory.php
 *
 * @purpose   : This is the factory class for PlanningManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Service\PlanningManager;

use PlanningBus\Model\PlanningTable;
use PlanningBus\Model\TypePlanningTable;
use PlanningBus\Model\DatePlanningTable;
use PlanningBus\Model\HeurePlanningTable;


/*
 * 
 */
class PlanningManagerFactory implements FactoryInterface
{

  /*
   * This method creates the PlanningManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $planningTable      = $container->get(PlanningTable::class);
    $typePlanningTable  = $container->get(TypePlanningTable::class);
    $datePlanningTable  = $container->get(DatePlanningTable::class);
    $heurePlanningTable = $container->get(HeurePlanningTable::class);
    $viewRenderer       = $container->get('ViewRenderer');
    $config             = $container->get('Config');

    return new PlanningManager($planningTable, $typePlanningTable, $datePlanningTable, $heurePlanningTable, $viewRenderer, $config);
  }
}