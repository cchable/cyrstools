<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/PlanningFullTableFactory.php
 *
 * @purpose   : This is the factory class for PlanningTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Model\PlanningFullTable;
use PlanningBus\Model\PlanningFullTableGateway;


/*
 *
 */
class PlanningFullTableFactory implements FactoryInterface
{
	
  /*
   * This method creates the PlanningTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $tableGateway = $container->get(PlanningFullTableGateway::class);
    return new PlanningFullTable($tableGateway);
  }
}
