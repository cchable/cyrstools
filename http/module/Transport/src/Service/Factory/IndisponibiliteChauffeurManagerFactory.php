<?php
/**
 * @package   : module/Transport/src/Service/Factory/IndisponibiliteChauffeurManagerFactory.php
 *
 * @purpose   : This is the factory class for ChauffeurManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\IndisponibiliteChauffeurManager;
use Transport\Model\IndisponibiliteChauffeurTable;


/*
 * 
 */
class IndisponibiliteChauffeurManagerFactory implements FactoryInterface
{

  /*
   * This method creates the ChauffeurManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $indisponibiliteChauffeurTable = $container->get(IndisponibiliteChauffeurTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new IndisponibiliteChauffeurManager(
      $indisponibiliteChauffeurTable,
      $viewRenderer,
      $config
    );
  }
}