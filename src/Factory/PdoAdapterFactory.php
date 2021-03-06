<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\OAuth2\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\OAuth2\Adapter\PdoAdapter;
use ZF\OAuth2\Controller\Exception;
use Interop\Container\ContainerInterface;

class PdoAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!isset($config['zf-oauth2']['db']) || empty($config['zf-oauth2']['db'])) {
            throw new Exception\RuntimeException(
                    'The database configuration [\'zf-oauth2\'][\'db\'] for OAuth2 is missing'
                    );
        }

        $username = isset($config['zf-oauth2']['db']['username']) ? $config['zf-oauth2']['db']['username'] : null;
        $password = isset($config['zf-oauth2']['db']['password']) ? $config['zf-oauth2']['db']['password'] : null;
        $options  = isset($config['zf-oauth2']['db']['options']) ? $config['zf-oauth2']['db']['options'] : [];

        $oauth2ServerConfig = [];
        if (isset($config['zf-oauth2']['storage_settings']) && is_array($config['zf-oauth2']['storage_settings'])) {
            $oauth2ServerConfig = $config['zf-oauth2']['storage_settings'];
        }

        return new PdoAdapter([
            'dsn'      => $config['zf-oauth2']['db']['dsn'],
            'username' => $username,
            'password' => $password,
            'options'  => $options,
        ], $oauth2ServerConfig);
    }

    /**
     * @param ServiceLocatorInterface $services
     * @throws \ZF\OAuth2\Controller\Exception\RuntimeException
     * @return \ZF\OAuth2\Adapter\PdoAdapter
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return $this($services, PdoAdapterFactory::class);
    }
}
