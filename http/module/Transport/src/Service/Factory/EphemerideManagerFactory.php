<?php
/**
 * @package   : module/Transport/src/Service/Factory/EphemerideManagerFactory.php
 *
 * @purpose   : This is the factory class for EphemerideManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\EphemerideManager;
use Transport\Model\EphemerideTable;
use Transport\Model\AnneeScolaireTable;


/*
 * 
 */
class EphemerideManagerFactory implements FactoryInterface
{

  /*
   * This method creates the EphemerideManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $ephemerideTable    = $container->get(EphemerideTable::class);
    $anneeScolaireTable = $container->get(AnneeScolaireTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new EphemerideManager(
      $ephemerideTable,
      $anneeScolaireTable,
      $viewRenderer,
      $config
    );
  }
}