<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/GroupeTableFactory.php
 *
 * @purpose   : This is the factory class for GroupeTable service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Model\GroupeTable;
use PlanningBus\Model\GroupeTableGateway;


/*
 *
 */
class GroupeTableFactory implements FactoryInterface
{
  
  /*
   * This method creates the GroupeTable service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {        
  
    $tableGateway = $container->get(GroupeTableGateway::class);
    return new GroupeTable($tableGateway);
  }
}
