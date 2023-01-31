<?php
/**
 * This is the factory class for TrajetManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/TrajetManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\TrajetManager;
use Transport\Model\TrajetTable;
use Transport\Model\EtapeTable;



/*
 * 
 */
class TrajetManagerFactory implements FactoryInterface
{

  /*
   * This method creates the TrajetManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $trajetTable  = $container->get(TrajetTable::class);
    $etapbeTable  = $container->get(EtapeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new TrajetManager(
      $trajetTable, 
      $EtapeTable, 
      $viewRenderer, 
      $config
    );
  }
}