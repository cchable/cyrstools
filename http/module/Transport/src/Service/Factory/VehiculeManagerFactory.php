<?php
/**
 * This is the factory class for VehiculeManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/VehiculeManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\VehiculeManager;
use Transport\Model\VehiculeTable;
use Transport\Model\MarqueTable;
use Transport\Model\TypeVehiculeTable;


/*
 * 
 */
class VehiculeManagerFactory implements FactoryInterface
{

  /*
   * This method creates the VehiculeManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $vehiculeTable     = $container->get(VehiculeTable::class);
    $marqueTable       = $container->get(MarqueTable::class);
    $typeVehiculeTable = $container->get(TypeVehiculeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new VehiculeManager(
      $vehiculeTable, 
      $marqueTable,
      $typeVehiculeTable,
      $viewRenderer, 
      $config
    );
  }
}