<?php
/**
 * This is the factory class for GroupeTableGateway service. 
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @package   module/Transport/src/Service/Factory/GroupeTableGatewayFactory.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use Transport\Model;
use Transport\Model\Groupe;


/*
 * 
 */
class GroupeTableGatewayFactory implements FactoryInterface
{
	
  /*
   * This method creates the GroupeTableGateway service and returns its instance. 
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $dbAdapter = $container->get('Transport\Db\ReadWriteAdapter');
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Groupe());
    return new TableGateway('T_GROUPES', $dbAdapter, null, $resultSetPrototype);
  }
}
