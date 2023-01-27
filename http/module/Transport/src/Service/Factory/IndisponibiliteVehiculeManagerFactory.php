<?php
/**
 * This is the factory class for IndisponibiliteVehiculeManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/IndisponibiliteVehiculeManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\IndisponibiliteVehiculeManager;
use Transport\Model\IndisponibiliteVehiculeTable;
use Transport\Model\VehiculeTable;


/**
 * 
 */
class IndisponibiliteVehiculeManagerFactory implements FactoryInterface
{

  /**
   * This method creates the IndisponibiliteVehiculeManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $indisponibiliteVehiculeTable = $container->get(IndisponibiliteVehiculeTable::class);
    $vehiculeTable                = $container->get(VehiculeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new IndisponibiliteVehiculeManager(
      $indisponibiliteVehiculeTable,
      $vehiculeTable,
      $viewRenderer,
      $config
    );
  }
}