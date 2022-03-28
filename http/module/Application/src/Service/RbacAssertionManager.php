<?php

/**
 * @package    module/Application/src/Service/RbacAssertionManager.php
 *
 * @purpose
 *  This service is used for invoking user-defined RBAC dynamic assertions.
 * 
 * @copyright  Copyright (C) 2018, 19 H.P.B
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Application\Service;

use Zend\Permissions\Rbac\Rbac;
use User\Model\UserTable;


class RbacAssertionManager
{
    /**
     * User table manager.
     * @var User\Model\UserTable
     */
    private $userTable;
    
    /**
     * Auth service.
     * @var Zend\Authentication\AuthenticationService 
     */
    private $authService;
    
    /**
     * Constructs the service.
     */
    public function __construct($userTable, $authService) 
    {
      $this->userTable   = $userTable;
      $this->authService = $authService;
    }
    
    /**
     * This method is used for dynamic assertions. 
     */
    public function assert(Rbac $rbac, $permission, $params)
    {
      $currentUser = $this->userTable->findOneByEmail($this->authService->getIdentity());
      
      if ($permission=='profile.own.view' && $params['user']->getId()==$currentUser->getId())
        return true;
      
      return false;
    }
}



