<?php
/**
 * This is the factory for Vehicule Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/VehiculeControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\VehiculeController;
use Transport\Service\VehiculeManager;
use Transport\Model\VehiculeTable;
use Transport\Model\ViewVehiculeTable;


/**
 * 
 */
class VehiculeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $vehiculeTable     = $container->get(VehiculeTable::class);
    $viewVehiculeTable = $container->get(ViewVehiculeTable::class);
    $vehiculeManager   = $container->get(VehiculeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer  = $container->get('VehiculeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new VehiculeController(
      $vehiculeTable,
      $viewVehiculeTable,
      $vehiculeManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer
    );
  }
}