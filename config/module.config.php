<?php // @codingStandardsIgnoreFile

use Zend\Router\Http\Literal;

use ZF\OAuth2;

return array(
    'controllers' => array(
        'factories' => array(
            'ZF\OAuth2\Controller\Auth' => OAuth2\Factory\AuthControllerFactory::class,
        ),
    ),
    'router' => array(
        'routes' => array(
            'oauth' => array(
                'type' => Literal::class,
                'options' => array(
                    'route'    => '/oauth',
                    'defaults' => array(
                        'controller' => OAuth2\Controller\Auth::class,
                        'action'     => 'token',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'authorize' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/authorize',
                            'defaults' => array(
                                'action' => 'authorize',
                            ),
                        ),
                    ),
                    'resource' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/resource',
                            'defaults' => array(
                                'action' => 'resource',
                            ),
                        ),
                    ),
                    'code' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/receivecode',
                            'defaults' => array(
                                'action' => 'receiveCode',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'ZF\OAuth2\Provider\UserId' => OAuth2\Provider\UserId\AuthenticationService::class,
        ),
        'factories' => array(
            'ZF\OAuth2\Adapter\PdoAdapter'    => OAuth2\Factory\PdoAdapterFactory::class,
            'ZF\OAuth2\Adapter\IbmDb2Adapter' => OAuth2\Factory\IbmDb2AdapterFactory::class,
            'ZF\OAuth2\Adapter\MongoAdapter'  => OAuth2\Factory\MongoAdapterFactory::class,
            'ZF\OAuth2\Provider\UserId\AuthenticationService' => OAuth2\Provider\UserId\AuthenticationServiceFactory::class,
            'ZF\OAuth2\Service\OAuth2Server'  => OAuth2\Factory\OAuth2ServerFactory::class
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'oauth/authorize'    => __DIR__ . '/../view/zf/auth/authorize.phtml',
            'oauth/receive-code' => __DIR__ . '/../view/zf/auth/receive-code.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'zf-oauth2' => array(
        /*
         * Config can include:
         * - 'storage' => 'name of storage service' - typically ZF\OAuth2\Adapter\PdoAdapter
         * - 'db' => [ // database configuration for the above PdoAdapter
         *       'dsn'      => 'PDO DSN',
         *       'username' => 'username',
         *       'password' => 'password'
         *   ]
         * - 'storage_settings' => [ // configuration to pass to the storage adapter
         *       // see https://github.com/bshaffer/oauth2-server-php/blob/develop/src/OAuth2/Storage/Pdo.php#L57-L66
         *   ]
         */
        'grant_types' => array(
            'client_credentials' => true,
            'authorization_code' => true,
            'password'           => true,
            'refresh_token'      => true,
            'jwt'                => true,
        ),
        /*
         * Error reporting style
         *
         * If true, client errors are returned using the
         * application/problem+json content type,
         * otherwise in the format described in the oauth2 specification
         * (default: true)
         */
        'api_problem_error_response' => true,
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'ZF\OAuth2\Controller\Auth' => array(
                'ZF\ContentNegotiation\JsonModel' => array(
                    'application/json',
                    'application/*+json',
                ),
                'Zend\View\Model\ViewModel' => array(
                    'text/html',
                    'application/xhtml+xml',
                ),
            ),
        ),
    ),
);
