<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 */
namespace ZF\MvcRbac\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\MvcRbac\Authorization\RbacAuthorizationFactory as RbacFactory;

class RbacAuthorizationFactory implements FactoryInterface
{
    /**
     * Create the DefaultAuthorizationListener
     *
     * @param ServiceLocatorInterface $services
     * @return \ZF\MvcAuth\Authorization\AuthorizationInterface
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config = array();
        if ($services->has('config')) {
            $config = $services->get('config');
        }

        return $this->createAccessFromConfig($config);
    }

    protected function createAccessFromConfig(array $config)
    {
        $rbacConfig = array();
        if (isset($config['zf-mvc-auth']) && isset($config['zf-mvc-auth']['authorization'])) {
            $config = $config['zf-mvc-auth']['authorization'];
            if (array_key_exists('deny_by_default', $config)) {
                $denyByDefault = $aclConfig['deny_by_default'] = (bool) $config['deny_by_default'];
                unset($config['deny_by_default']);
            }
            foreach ($config as $controllerService => $privileges) {
                $this->createAccessConfigFromPrivileges($controllerService, $privileges, $rbacConfig);
            }
        }
        return RbacFactory::factory($rbacConfig);
    }

    protected function createAccessConfigFromPrivileges($controllerService, array $privileges, &$rbacConfig)
    {
        foreach ($privileges as $role => $resources) {
            $permissions = array();
            if (isset($resources['actions'])) {
                $permissions = $this->createPermissionsFromActions($controllerService, $resources['actions']);
            }

            if (isset($resources['collection']) || isset($resources['entity'])) {
                $permissions = $this->createPermissionsFromResources($controllerService, $resources);
            }
            foreach ($permissions as $permission) {
                $rbacConfig[$role][] = $permission;
            }

        }
    }

    protected function createPermissionsFromActions($controllerService, $actions)
    {
        $permissions = array();
        foreach ($actions as $action => $methods) {
            foreach ($methods as $method => $flag) {
                if ((bool)$flag) {
                    $permissions[] = sprintf('%s.%s::%s', $method, $controllerService, $action);
                }
            }
        }

        return $permissions;
    }

    protected function createPermissionsFromResources($controllerService, array $resources)
    {
        $permissions = array();
        foreach ($resources as $type => $resource) {
            foreach ($resource as $method => $flag) {
                if ((bool)$flag) {
                    $permissions[] = sprintf('%s.%s::%s', $method, $controllerService, $type);
                }
            }
        }
        return $permissions;
    }
}