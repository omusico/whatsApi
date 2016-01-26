<?php
return array(
    'router' => array(
        'routes' => array(
            'captcha' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/captcha[/:action]/[:id]',
                    'defaults' => array(
                        'controller' => 'Common\Controller\Captcha',
                        'action' => 'index',
                        'id' => '0'
                    )
                )
            ),
            'general' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/general[/:action]/[:id]',
                    'defaults' => array(
                        'controller' => 'Common\Controller\General',
                        'action' => 'index'
                    )
                )
            )
            
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        )
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'common_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Common/Entity'
                )
            ),
            
            'orm_default' => array(
                'drivers' => array(
                    'Common\Entity' => 'common_entities'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Common\Entity\Usuarios',
                'identity_property' => 'nomeUsuario',
                'credential_property' => 'senhaUsuario',
                'credential_callable' => function ($user, $pass) {
                    return ($user->getIdStatusUsuarios()->getIdStatusUsuarios() == 4  || $user->getIdStatusUsuarios()->getIdStatusUsuarios() == 7 ) && $user->getSenhaUsuario() == $pass;
                }
            )
        )
    ),
);