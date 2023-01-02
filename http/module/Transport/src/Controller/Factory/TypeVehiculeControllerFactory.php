<?php
/**
 * This is the factory for TYpeVehicule Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/TypeVehiculeFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\TypeVehiculeController;
use Transport\Service\TypeVehiculeManager;
use Transport\Model\TypeVehiculeTable;


/*
 * 
 */
class TypeVehiculeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $typeVehiculeTable   = $container->get(TypeVehiculeTable::class);
    $typeVehiculeManager = $container->get(TypeVehiculeManager::class);
    
    $config               = $container->get('Config');
    $defaultRowPerPage    = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage       = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer     = $container->get('TypeVehiculeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new TypeVehiculeController(
      $typeVehiculeTable,
      $typeVehiculeManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer,
    );
  }
}