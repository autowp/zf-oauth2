<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\OAuth2\Provider\UserId;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if ($container->has('Zend\Authentication\AuthenticationService')) {
            return new AuthenticationService(
                $container->get('Zend\Authentication\AuthenticationService'),
                $config
            );
        }

        return new AuthenticationService(null, $config);
    }

    /**
     * @param ServiceLocatorInterface $services
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return $this($services, AuthenticationServiceFactory::class);
    }
}
