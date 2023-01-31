<?php
/**
 * This is the factory class for TrajetTableGateway service. 
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @package   module/Transport/src/Service/Factory/TrajetTableGatewayFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\TrajetTable;
use Transport\Model\TrajetTableGateway;


/*
 *
 */
class TrajetTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the TrajetTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(TrajetTableGateway::class);
    return new TrajetTable($tableGateway);
  }
}
