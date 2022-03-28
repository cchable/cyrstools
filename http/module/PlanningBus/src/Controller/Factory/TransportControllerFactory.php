<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/TransportControllerFactory.php
 *
 * @purpose   : This is the factory for TransportController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\TransportController;
use PlanningBus\Service\TransportManager;
use PlanningBus\Model\TransportTable;
use PlanningBus\Model\TransportFullTable;


/*
 * 
 */
class TransportControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $transportTable     = $container->get(TransportTable::class);
    $transportFullTable = $container->get(TransportFullTable::class);
    $transportManager   = $container->get(TransportManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('TransportSessionContainer');
    // Instantiate the controller and inject dependencies
    return new TransportController($transportTable, $transportFullTable, $transportManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}