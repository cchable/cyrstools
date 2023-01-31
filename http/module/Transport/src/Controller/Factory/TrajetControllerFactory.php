<?php
/**
 * This is the factory for Trajet Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/TrajetControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\TrajetController;
use Transport\Service\TrajetManager;
use Transport\Model\TrajetTable;
use Transport\Model\ViewTrajetTable;


/**
 * 
 */
class TrajetControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $trajetTable     = $container->get(TrajetTable::class);
    $viewTrajetTable = $container->get(ViewTrajetTable::class);
    $trajetManager   = $container->get(TrajetManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer  = $container->get('TrajetSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new TrajetController(
      $trajetTable,
      $viewTrajetTable,
      $trajetManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer
    );
  }
}