<?php
/**
 * This is the factory for IndisponibiliteVehicule Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/IndisponibiliteVehiculeControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\IndisponibiliteVehiculeController;
use Transport\Service\IndisponibiliteVehiculeManager;
use Transport\Model\IndisponibiliteVehiculeTable;
use Transport\Model\ViewIndisponibiliteVehiculeTable;


/*
 * 
 */
class IndisponibiliteVehiculeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $indisponibiliteVehiculeTable     = $container->get(IndisponibiliteVehiculeTable::class);
    $viewIndisponibiliteVehiculeTable = $container->get(ViewIndisponibiliteVehiculeTable::class);
    $indisponibiliteVehiculeManager   = $container->get(IndisponibiliteVehiculeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer  = $container->get('IndisponibiliteVehiculeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new IndisponibiliteVehiculeController(
      $indisponibiliteVehiculeTable,
      $viewIndisponibiliteVehiculeTable,
      $indisponibiliteVehiculeManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer,
    );
  }
}