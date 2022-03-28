<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/DatePlanningManagerFactory.php
 *
 * @purpose   : This is the factory class for DatePlanningManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Service\DatePlanningManager;
use PlanningBus\Model\DatePlanningTable;


/*
 * 
 */
class DatePlanningManagerFactory implements FactoryInterface
{

  /*
   * This method creates the DatePlanningManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $datePlanningTable = $container->get(DatePlanningTable::class);
    $viewRenderer      = $container->get('ViewRenderer');
    $config            = $container->get('Config');

    return new DatePlanningManager($datePlanningTable, $viewRenderer, $config);
  }
}