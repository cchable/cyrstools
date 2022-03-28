<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/GroupeControllerFactory.php
 *
 * @purpose   : This is the factory for GroupeController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\GroupeController;
use PlanningBus\Service\GroupeManager;
use PlanningBus\Model\GroupeTable;


/*
 * 
 */
class GroupeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $groupeTable   = $container->get(GroupeTable::class);
    $groupeManager = $container->get(GroupeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('GroupeSessionContainer');    
    
    // Instantiate the controller and inject dependencies
    return new GroupeController($groupeTable, $groupeManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}