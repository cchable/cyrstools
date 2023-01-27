<?php
/**
 * This is the factory for Etape Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/EtapeControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\EtapeController;
use Transport\Service\EtapeManager;
use Transport\Model\EtapeTable;
use Transport\Model\ViewEtapeTable;


/**
 * 
 */
class EtapeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $etapeTable   = $container->get(EtapeTable::class);
    $etapeManager = $container->get(EtapeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer  = $container->get('EtapeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new EtapeController(
      $etapeTable,
      $etapeManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer
    );
  }
}