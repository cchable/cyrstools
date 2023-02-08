<?php
/**
 * This is the factory class for GroupeManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/GroupeManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\GroupeManager;
use Transport\Model\GroupeTable;


/**
 * 
 */
class GroupeManagerFactory implements FactoryInterface
{

  /**
   * This method creates the GroupeManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $groupeTable  = $container->get(GroupeTable::class);

    return new GroupeManager(
      $groupeTable,
    );
  }
}