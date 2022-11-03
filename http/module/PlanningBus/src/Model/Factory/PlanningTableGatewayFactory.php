<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/PlanningTableGatewayFactory.php
 *
 * @purpose   : This is the factory class for PlanningTableGateway service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright  Copyright (C) 2018-21 H.P.B
 *
 * @license    GNU General Public License version2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use PlanningBus\Model;
use PlanningBus\Model\Planning;


/*
 * 
 */
class PlanningTableGatewayFactory implements FactoryInterface
{
	
  /*
   * This method creates the PlanningTableGateway service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $dbAdapter = $container->get('PlanningBus\Db\ReadWriteAdapter');
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Planning());
    return new TableGateway('T_PLANNINGS', $dbAdapter, null, $resultSetPrototype);
  }
}