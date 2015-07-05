<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 */
return array(
    'service_manager' => array(
        'aliases' => array(
            'authentication' => 'ZF\MvcAuth\Authentication',
            'authorization' => 'ZF\MvcAuth\Authorization\AuthorizationInterface',
            'ZF\MvcAuth\Authorization\AuthorizationInterface' => 'ZF\MvcAuth\Authorization\RbacAuthorization',
        ),
        'factories' => array(
            'ZF\MvcAuth\Authorization\RbacAuthorization' => 'ZF\MvcRbac\Factory\RbacAuthorizationFactory',
        ),
    ),
);