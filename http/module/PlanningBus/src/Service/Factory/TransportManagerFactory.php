<?php
/**
 * @package   : module/PlanningBus/src/Service/Factory/TransportManagerFactory.php
 *
 * @purpose   : This is the factory class for TransportManager service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use PlanningBus\Service\TransportManager;
use PlanningBus\Model\TransportTable;


/*
 * 
 */
class TransportManagerFactory implements FactoryInterface
{

  /*
   * This method creates the TransportManager service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $trajetTable  = $container->get(TransportTable::class);
    $viewRenderer = $container->get('ViewRenderer');
    $config       = $container->get('Config');

    return new TransportManager($trajetTable, $viewRenderer, $config);
  }
}