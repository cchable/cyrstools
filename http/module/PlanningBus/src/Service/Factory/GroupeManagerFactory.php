<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/GroupeManagerFactory.php
 *
 * @purpose   : This is the factory class for GroupeManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Service\GroupeManager;
use PlanningBus\Model\GroupeTable;


/*
 * 
 */
class GroupeManagerFactory implements FactoryInterface
{

  /*
   * This method creates the GroupeManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $groupeTable  = $container->get(GroupeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new GroupeManager($groupeTable, $viewRenderer, $config);
  }
}