<?php
/**
 * @package   : module/Application/src/View/Helper/Factory/MenuFactory.php
 *
 * @purpose   : This is the factory for Menu view helper. Its purpose is to instantiate the
 *              helper and init menu items.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Application\View\Helper\Menu;
use Application\Service\NavManager;


/*
 * 
 */
class MenuFactory implements FactoryInterface
{
  
  /*
   * 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
    
    $navManager = $container->get(NavManager::class);

    // Get menu items.
    $items = $navManager->getMenuItems();

    // Instantiate the helper.
    return new Menu($items);
  }
}

