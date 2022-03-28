<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/TrajetControllerFactory.php
 *
 * @purpose   : This is the factory for TrajetController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\TrajetController;
use PlanningBus\Service\TrajetManager;
use PlanningBus\Model\TrajetTable;
use PlanningBus\Model\TrajetFullTable;


/*
 * 
 */
class TrajetControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $trajetTable     = $container->get(TrajetTable::class);
    $trajetFullTable = $container->get(TrajetFullTable::class);
    $trajetManager   = $container->get(TrajetManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('TrajetSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new TrajetController($trajetTable, $trajetFullTable, $trajetManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}