<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 */
namespace ZF\MvcRbac\Factory;

use Zend\Http\Request;
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
            foreach ($config as $controllerService => $privileges) {
                $this->createAccessConfigFromPrivileges($controllerService, $privileges, $rbacConfig);
            }
        }
        return RbacFactory::factory($rbacConfig);
    }

    protected function createAccessConfigFromPrivileges($controllerService, array $privileges, &$rbacConfig)
    {
        foreach ($privileges as $role => $resources) {
            $rbacConfig[$role][] = $this->createPermissionsFromResources($controllerService, $resources);
        }
    }

    protected function createPermissionsFromResources($controllerService, array $resources)
    {
        $permissions = array();
        foreach ($resources as $resource) {
            foreach ($resource as $method => $flag) {
                if ((bool)$flag) {
                    $permissions[] = sprintf('%s.%s::%s', $method, $controllerService, $resource);
                }
            }
        }
        return $permissions;
    }
}