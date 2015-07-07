<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 */
namespace ZF\MvcRbac\Authorization;

abstract class RbacAuthorizationFactory
{
    public static function factory(array $config)
    {
        $rbac = new RbacAuthorization();
        $rbac->addRole('guest');
        foreach ($config as $role => $permissions) {
            if (!$rbac->hasRole($role)) {
                $rbac->addRole($role);
            }
            foreach ($permissions as $permission) {
                $rbac->getRole($role)->addPermission($permission);
            }
        }
        return $rbac;
    }
}