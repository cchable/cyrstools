<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/TrajetTableGatewayFactory.php
 *
 * @purpose   : This is the factory class for TrajetTableGateway service. The purpose of the factory
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
use PlanningBus\Model\Trajet;


/*
 * 
 */
class TrajetTableGatewayFactory implements FactoryInterface
{
	
  /*
   * This method creates the TrajetTableGateway service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $dbAdapter = $container->get('PlanningBus\Db\ReadWriteAdapter');
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Trajet());
    return new TableGateway('T_TRAJETS', $dbAdapter, null, $resultSetPrototype);
  }
}
