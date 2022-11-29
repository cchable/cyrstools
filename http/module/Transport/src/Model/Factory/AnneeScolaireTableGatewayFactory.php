<?php
/**
 * @package   : module/Transport/src/Service/Factory/AnneeScolaireTableGatewayFactory.php
 *
 * @purpose   : This is the factory class for AnneeScolaireTableGateway service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 * 
 * @license   : GNU General Public License version2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use Transport\Model;
use Transport\Model\AnneeScolaire;


/*
 * 
 */
class AnneeScolaireTableGatewayFactory implements FactoryInterface
{
	
  /*
   * This method creates the AnneeScolaireTableGateway service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $dbAdapter = $container->get('Transport\Db\ReadWriteAdapter');
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new AnneeScolaire());
    return new TableGateway('T_ANNEESSCOLAIRES', $dbAdapter, null, $resultSetPrototype);
  }
}
