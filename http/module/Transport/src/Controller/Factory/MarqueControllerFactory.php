<?php
/**
 * This is the factory for Marque Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/MarqueFactory.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\MarqueController;
use Transport\Service\MarqueManager;
use Transport\Model\MarqueTable;


/*
 * 
 */
class MarqueControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $marqueTable   = $container->get(MarqueTable::class);
    $marqueManager = $container->get(MarqueManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer  = $container->get('MarqueSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new MarqueController(
      $marqueTable,
      $marqueManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer,
    );
  }
}