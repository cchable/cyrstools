<?php
/**
 * @package   : module/Transport/src/Controller/Factory/IndisponibiliteChauffeurControllerFactory.php
 *
 * @purpose   : This is the factory for ChauffeurController. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\IndisponibiliteChauffeurController;
use Transport\Service\IndisponibiliteChauffeurManager;
use Transport\Model\IndisponibiliteChauffeurTable;


/*
 * 
 */
class IndisponibiliteChauffeurControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $indisponibiliteChauffeurTable   = $container->get(IndisponibiliteChauffeurTable::class);
    $indisponibiliteChauffeurManager = $container->get(IndisponibiliteChauffeurManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer  = $container->get('IndisponibiliteChauffeurSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new IndisponibiliteChauffeurController(
      $indisponibiliteChauffeurTable,
      $indisponibiliteChauffeurManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer,
    );
  }
}