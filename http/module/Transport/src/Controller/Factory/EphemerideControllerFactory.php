<?php
/**
 * This is the factory for Ephemeride Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/EphemerideFactory.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\EphemerideController;
use Transport\Service\EphemerideManager;
use Transport\Model\EphemerideTable;
use Transport\Model\ViewEphemerideTable;


/**
 * 
 */
class EphemerideControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $ephemerideTable     = $container->get(EphemerideTable::class);
    $viewEphemerideTable = $container->get(ViewEphemerideTable::class);
    $ephemerideManager   = $container->get(EphemerideManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer  = $container->get('EphemerideSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new EphemerideController(
      $ephemerideTable,
      $viewEphemerideTable,
      $ephemerideManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer,
    );
  }
}