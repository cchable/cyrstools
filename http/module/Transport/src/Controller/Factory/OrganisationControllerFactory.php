<?php
/**
 * This is the factory for Organisation Controller.
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 *
 * @package   module/Transport/src/Controller/Factory/OrganisationControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\OrganisationController;
use Transport\Service\OrganisationManager;
use Transport\Model\OrganisationTable;
use Transport\Model\ViewOrganisationTable;


/**
 *
 */
class OrganisationControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $organisationTable     = $container->get(OrganisationTable::class);
    $viewOrganisationTable = $container->get(ViewOrganisationTable::class);
    $organisationManager   = $container->get(OrganisationManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer  = $container->get('OrganisationSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new OrganisationController(
      $organisationTable,
      $viewOrganisationTable,
      $organisationManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer
    );
  }
}