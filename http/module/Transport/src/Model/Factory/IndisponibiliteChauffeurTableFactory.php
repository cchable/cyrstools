<?php
/**
 * @package   : module/Transport/src/Service/Factory/IndisponibiliteChauffeurTableFactory.php
 *
 * @purpose   : This is the factory class for ChauffeurTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Model\IndisponibiliteChauffeurTable;
use Transport\Model\IndisponibiliteChauffeurTableGateway;


/*
 *
 */
class IndisponibiliteChauffeurTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the IndisponibiliteChauffeurTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(IndisponibiliteChauffeurTableGateway::class);
    return new IndisponibiliteChauffeurTable($tableGateway);
  }
}
