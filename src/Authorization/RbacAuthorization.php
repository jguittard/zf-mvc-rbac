<?php

namespace ZF\MvcRbac\Authorization;


use Zend\Permissions\Rbac\Rbac;
use ZF\MvcAuth\Authorization\AuthorizationInterface;
use ZF\MvcAuth\Identity\IdentityInterface;

class RbacAuthorization extends Rbac implements AuthorizationInterface
{
    /**
     * Whether or not the given identity has the given privilege on the given resource.
     *
     * @param IdentityInterface $identity
     * @param mixed $resource
     * @param mixed $privilege
     * @return bool
     */
    public function isAuthorized(IdentityInterface $identity, $resource, $privilege)
    {
        $role = $identity->getRoleId();
        $permission = sprintf('%s.%s', $privilege, $resource);
        return $this->isGranted($role, $permission);
    }

}