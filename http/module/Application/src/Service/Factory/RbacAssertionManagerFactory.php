<?php

/**
 * @package    module/Application/src/Service/Helper/RbacAssertionManagerFactory.php
 *
 * @purpose
 *  This is the factory class for RbacAssertionManager service. The purpose of the factory
 *  is to instantiate the service and pass it dependencies (inject dependencies).
 * 
 * @copyright  Copyright (C) 2018, 19 H.P.B
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\RbacAssertionManager;
use User\Model\UserTable;


class RbacAssertionManagerFactory
{
    /**
     * This method creates the RbacAssertionManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $userTable   = $container->get(UserTable::class);
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        
        return new RbacAssertionManager($userTable, $authService);
    }
}
