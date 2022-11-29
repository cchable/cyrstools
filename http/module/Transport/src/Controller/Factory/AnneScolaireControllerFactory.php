<?php
/**
 * @package   : module/Transport/src/Controller/Factory/AnneeScolaireFactory.php
 *
 * @purpose   : This is the factory for AnneeScolaire. Its purpose is to instantiate the controller
 *              and inject dependencies into its constructor.
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Transport\Controller\AnneeScolaire;
use Transport\Service\AnneeScolaireManager;
use Transport\Model\AnneeScolaireTable;


/*
 * 
 */
class AnneeScolaireFactory implements FactoryInterface
{
  
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {   
  
    $anneeScolaireTable   = $container->get(AnneeScolaireTable::class);
    $anneeScolaireManager = $container->get(AnneeScolaireManager::class);
    
    $config             = $container->get('Config');
    $defaultRowPerPage  = $config['paginator']['options']['defaultRowPerPage'];
    $stepRowPerPage     = $config['paginator']['options']['stepRowPerPage'];
      
    $sessionContainer   = $container->get('AnneeScolaireSessionContainer');
    
    // Instantiate the controller and inject dependencies
    return new AnneeScolaire(
      $anneeScolaireTable,
      $anneeScolaireManager,
      $defaultRowPerPage,
      $stepRowPerPage,
      $sessionContainer,
    );
  }
}