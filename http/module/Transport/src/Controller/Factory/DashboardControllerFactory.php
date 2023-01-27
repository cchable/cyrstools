<?php
/**
 * This is the factory for Dashboard Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/DashboardControllerFactory.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\DashboardController;
use Transport\Model\ChauffeurTable;
use Transport\Model\MarqueTable;
use Transport\Model\TypeVehiculeTable;
use Transport\Model\VehiculeTable;
use Transport\Model\EtapeTable;


/**
 * 
 */
class DashboardControllerFactory implements FactoryInterface
{
  
  /**
   * 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $chauffeurTable    = $container->get(ChauffeurTable::class);
    $marqueTable       = $container->get(MarqueTable::class);
    $typeVehiculeTable = $container->get(TypeVehiculeTable::class);
    $vehiculeTable     = $container->get(VehiculeTable::class);
    $etapeTable        = $container->get(EtapeTable::class);
    
    $sessionContainer  = $container->get('DashboardSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new DashboardController(
      $chauffeurTable, 
      $marqueTable,
      $typeVehiculeTable,
      $vehiculeTable,
      $etapeTable,
      $sessionContainer
    );
  }
}