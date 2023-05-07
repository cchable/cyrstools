<?php
/**
 * This is the factory class for OrganisarionManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/OrganisarionManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\OrganisarionManager;
use Transport\Model\OrganisarionTable;
use Transport\Model\GroupeTable;


/*
 * 
 */
class OrganisarionManagerFactory implements FactoryInterface
{

  /*
   * This method creates the OrganisarionManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $organisarionTable  = $container->get(OrganisarionTable::class);
    $groupeTable  = $container->get(GroupeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new OrganisarionManager(
      $organisarionTable, 
      $groupeTable,
      $viewRenderer, 
      $config
    );
  }
}