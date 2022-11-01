<?php
/**
 * @package   : module/Transport/src/Service/Factory/MarqueTableFactory.php
 *
 * @purpose   : This is the factory class for MarqueTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\MarqueTable;
use Transport\Model\MarqueTableGateway;


/*
 *
 */
class MarqueTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the MarqueTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(MarqueTableGateway::class);
    return new MarqueTable($tableGateway);
  }
}
