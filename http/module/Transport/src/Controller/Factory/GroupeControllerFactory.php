<?php
/**
 * This is the factory for Groupe Controller. 
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 * 
 * @package   module/Transport/src/Controller/Factory/GroupeControllerFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\GroupeController;
use Transport\Service\GroupeManager;
use Transport\Model\GroupeTable;


/**
 * 
 */
class GroupeControllerFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $groupeTable     = $container->get(GroupeTable::class);
    $groupeManager   = $container->get(GroupeManager::class);
    
    $config            = $container->get('Config');
    $defaultRowPerPage = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage    = $config['paginator']['options']['stepRowPerPage'];
    
    $sessionContainer  = $container->get('GroupeSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new GroupeController(
      $groupeTable,
      $groupeManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer
    );
  }
}