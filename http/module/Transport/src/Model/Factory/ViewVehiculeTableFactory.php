<?php
/**
 * This is the factory class for ViewVehiculeTable service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @package   module/Transport/src/Service/Factory/ViewVehiculeTableFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\ViewVehiculeTable;
use Transport\Model\ViewVehiculeTableGateway;


/*
 *
 */
class ViewVehiculeTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the ViewVehiculeTable model and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
 
    $tableGateway = $container->get(ViewVehiculeTableGateway::class);
    return new ViewVehiculeTable($tableGateway); 
    
  }
}
