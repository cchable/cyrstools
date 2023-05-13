<?php
/**
 * This is the factory class for OrganisationManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/OrganisationManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\OrganisationManager;
use Transport\Model\OrganisationTable;
use Transport\Model\GroupeTable;


/*
 * 
 */
class OrganisationManagerFactory implements FactoryInterface
{

  /*
   * This method creates the OrganisationManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $organisationTable  = $container->get(OrganisationTable::class);
    $groupeTable  = $container->get(GroupeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new OrganisationManager(
      $organisationTable, 
      $groupeTable,
      $viewRenderer, 
      $config
    );
  }
}