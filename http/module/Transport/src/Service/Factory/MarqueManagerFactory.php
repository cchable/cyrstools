<?php
/**
 * This is the factory class for MarqueManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/MarqueManagerFactory.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\MarqueManager;
use Transport\Model\MarqueTable;


/*
 * 
 */
class MarqueManagerFactory implements FactoryInterface
{

  /*
   * This method creates the MarqueManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $marqueTable  = $container->get(MarqueTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new MarqueManager(
      $marqueTable,
      $viewRenderer,
      $config
    );
  }
}