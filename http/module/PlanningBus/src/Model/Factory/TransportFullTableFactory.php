<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/TransportFullTableFactory.php
 *
 * @purpose   : This is the factory class for TransportTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Model\TransportFullTable;
use PlanningBus\Model\TransportFullTableGateway;


/*
 *
 */
class TransportFullTableFactory implements FactoryInterface
{
	
  /*
   * This method creates the TransportTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $tableGateway = $container->get(TransportFullTableGateway::class);
    return new TransportFullTable($tableGateway);
  }
}
