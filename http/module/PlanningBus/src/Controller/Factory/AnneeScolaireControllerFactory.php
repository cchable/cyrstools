<?php
/**
 * @package   : module/PlanningBus/src/Controller/Factory/AnneeScolaireControllerFactory.php
 *
 * @purpose   : This is the factory for AnneeScolaireController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Controller\AnneeScolaireController;
use PlanningBus\Service\AnneeScolaireManager;
use PlanningBus\Model\AnneeScolaireTable;


/*
 * 
 */
class AnneeScolaireControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $anneeScolaireTable   = $container->get(AnneeScolaireTable::class);
    $anneeScolaireManager = $container->get(AnneeScolaireManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer = $container->get('AnneeScolaireSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new AnneeScolaireController($anneeScolaireTable, $anneeScolaireManager, $defaultRowPerPage, $stepRowPerPage, $sessionContainer);
  }
}