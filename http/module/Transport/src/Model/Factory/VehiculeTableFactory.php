<?php
/**
 * @package   : module/Transport/src/Service/Factory/VehiculeTableFactory.php
 *
 * @purpose   : This is the factory class for VehiculeTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\VehiculeTable;
use Transport\Model\VehiculeTableGateway;


/*
 *
 */
class VehiculeTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the VehiculeTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(VehiculeTableGateway::class);
    return new VehiculeTable($tableGateway);
  }
}
