<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/EtapeTableFactory.php
 *
 * @purpose   : This is the factory class for EtapeTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Model\EtapeTable;
use PlanningBus\Model\EtapeTableGateway;


/*
 *
 */
class EtapeTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the EtapeTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(EtapeTableGateway::class);
    return new EtapeTable($tableGateway);
  }
}
