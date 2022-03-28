<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/DatePlanningTableGatewayFactory.php
 *
 * @purpose   : This is the factory class for DatePlanningTableGateway service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use PlanningBus\Model;
use PlanningBus\Model\DatePlanning;


/*
 * 
 */
class DatePlanningTableGatewayFactory implements FactoryInterface
{
	
  /*
   * This method creates the DatePlanningTableGateway service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $dbAdapter = $container->get('PlanningBus\Db\ReadWriteAdapter');
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new DatePlanning());
    return new TableGateway('T_DATESPLANNINGS', $dbAdapter, null, $resultSetPrototype);
  }
}
