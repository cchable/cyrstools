<?php
/**
 * This is the factory class for IndisponibiliteVehiculeTable service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @package   module/Transport/src/Service/Factory/IndisponibiliteVehiculeTableFactory.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\IndisponibiliteVehiculeTable;
use Transport\Model\IndisponibiliteVehiculeTableGateway;


/*
 *
 */
class IndisponibiliteVehiculeTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the IndisponibiliteVehiculeTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(IndisponibiliteVehiculeTableGateway::class);
    return new IndisponibiliteVehiculeTable($tableGateway);
  }
}
