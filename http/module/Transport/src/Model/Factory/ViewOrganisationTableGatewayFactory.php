<?php
/**
 * @package   : module/Transport/src/Service/Factory/ViewOrganisationTableGatewayFactory.php
 *
 * @purpose   : This is the factory class for ViewOrganisationTableGateway service. The purpose of the factory
 *              is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use Transport\Model;
use Transport\Model\ViewOrganisation;


/*
 *
 */
class ViewOrganisationTableGatewayFactory implements FactoryInterface
{

  /*
   * This method creates the ViewOrganisationTableGateway service and returns its instance.
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
		
    $dbAdapter = $container->get('Transport\Db\ReadWriteAdapter');
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new ViewOrganisation());
    return new TableGateway('V_ORGANISATIONS', $dbAdapter, null, $resultSetPrototype);
  }
}
