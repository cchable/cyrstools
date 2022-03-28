<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/EphemerideControllerFactory.php
 *
 * @purpose   : This is the factory for EphemerideController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\EphemerideController;
use PlanningBus\Service\EphemerideManager;
use PlanningBus\Model\EphemerideTable;
use PlanningBus\Model\EphemerideFullTable;


/*
 * 
 */
class EphemerideControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $ephemerideTable     = $container->get(EphemerideTable::class);
    $ephemerideFullTable = $container->get(EphemerideFullTable::class);
    $ephemerideManager   = $container->get(EphemerideManager::class);
    
    $config              = $container->get('Config');
    $defaultRowPerPage   = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage      = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer    = $container->get('EphemerideSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new EphemerideController($ephemerideTable, $ephemerideFullTable, $ephemerideManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}