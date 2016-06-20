<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */
namespace ZF\OAuth2\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

class OAuth2ServerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $config = isset($config['zf-oauth2']) ? $config['zf-oauth2'] : [];
        return new OAuth2ServerInstanceFactory($config, $container);
    }

    /**
     * @param ServiceLocatorInterface $services
     * @return OAuth2\Server
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return $this($services, OAuth2ServerFactory::class);
    }
}
