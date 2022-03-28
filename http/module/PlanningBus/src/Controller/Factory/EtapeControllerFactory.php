<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/EtapeControllerFactory.php
 *
 * @purpose   : This is the factory for EtapeController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\EtapeController;
use PlanningBus\Service\EtapeManager;
use PlanningBus\Model\EtapeTable;


/*
 * 
 */
class EtapeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $etapeTable   = $container->get(EtapeTable::class);
    $etapeManager = $container->get(EtapeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('EtapeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new EtapeController($etapeTable, $etapeManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}