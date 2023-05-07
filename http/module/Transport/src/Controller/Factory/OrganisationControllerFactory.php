<?php
/**
 * This is the factory for Organisarion Controller.
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 *
 * @package   module/Transport/src/Controller/Factory/OrganisarionControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\OrganisarionController;
use Transport\Service\OrganisarionManager;
use Transport\Model\OrganisarionTable;
use Transport\Model\ViewOrganisarionTable;


/**
 * 
 */
class OrganisarionControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $organisarionTable     = $container->get(OrganisarionTable::class);
    $viewOrganisarionTable = $container->get(ViewOrganisarionTable::class);
    $organisarionManager   = $container->get(OrganisarionManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer  = $container->get('OrganisarionSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new OrganisarionController(
      $organisarionTable,
      $viewOrganisarionTable,
      $organisarionManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer
    );
  }
}