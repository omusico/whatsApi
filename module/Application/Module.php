<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Mvc\Router\RouteMatch;

class Module
{
    protected $sessionConfig = array(
        'remember_me_seconds' => 360,
        'use_cookies' => true,
        'cookie_httponly' => true
    );
    
    public function onBootstrap(MvcEvent $e)
    {   
        $this->initSession();
        
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $list = array();
        
        $auth = $e->getApplication()->getServiceManager()->get("Zend\Authentication\AuthenticationService");
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function ($e) use($list, $auth) {
            $match = $e->getRouteMatch();
            
            
            // No route match, this is a 404
            if (! $match instanceof RouteMatch) {
                return;
            }
            
            // Route is whitelisted
            $route = $match->getMatchedRouteName();
            $params = $match->getParams();
            $module = explode('/',$route);
            
            /*
            if($_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['SERVER_PORT'] != '443') {
                $url = 'Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                header(str_replace( 'www.', '' , $url));
                exit();
            }*/
            
                if( @$module[0] == 'login'){
                    if ($auth->hasIdentity()) {
                        $router = $e->getRouter();
                        $url = $router->assemble(array(), array(
                            'name' => 'messages'
                        ));
                        
                        $response = $e->getResponse();
                        $response->getHeaders()->addHeaderLine('Location', $url);
                        $response->setStatusCode(302);
                        
                        return $response;
                    }else{
                        return;
                    }
                }

                if($auth->hasIdentity()){
                    return;
                }else{
                    $router = $e->getRouter();
                    $url = $router->assemble(array(), array(
                        'name' => 'login'
                    ));
                    
                    $response = $e->getResponse();
                    $response->getHeaders()->addHeaderLine('Location', $url);
                    $response->setStatusCode(302);
                    
                    return $response;
                }
        
        }, - 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                }
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    public function initSession()
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($this->sessionConfig);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }
}
