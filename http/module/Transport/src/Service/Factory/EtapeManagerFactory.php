<?php
/**
 * This is the factory class for EtapeManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 *
 * @package   module/Transport/src/Service/Factory/EtapeManagerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Service\EtapeManager;
use Transport\Model\EtapeTable;
use Transport\Model\MarqueTable;
use Transport\Model\TypeEtapeTable;


/*
 * 
 */
class EtapeManagerFactory implements FactoryInterface
{

  /*
   * This method creates the EtapeManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $etapeTable   = $container->get(EtapeTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new EtapeManager(
      $etapeTable, 
      $viewRenderer, 
      $config
    );
  }
}