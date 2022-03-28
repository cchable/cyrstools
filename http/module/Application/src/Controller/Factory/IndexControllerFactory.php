<?php
/**
 * @package   : module/Application/src/Controller/Factory/IndexControllerFactory.php
 *
 * @purpose   : This is the factory for IndexController. Its purpose is to instantiate the
 *              controller and inject dependencies into it.
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Application\Controller\IndexController;


/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class IndexControllerFactory implements FactoryInterface
{
	
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
      
    // Instantiate the controller and inject dependencies
    return new IndexController();
  }
}