<?php
/**
 * This is the factory class for EphemerideTable service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @package   module/Transport/src/Service/Factory/EphemerideTableFactory.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\EphemerideTable;
use Transport\Model\EphemerideTableGateway;


/*
 *
 */
class EphemerideTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the EphemerideTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(EphemerideTableGateway::class);
    return new EphemerideTable($tableGateway);
  }
}
