<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 */
return array(
    'service_manager' => array(
        'factories' => array(
            'ZF\MvcAuth\Authorization\AclAuthorization' => 'ZF\MvcRbac\Factory\AclAuthorizationFactory',
        ),
    ),
);