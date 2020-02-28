<?php
declare(strict_types=1);
namespace Financas\Plugins;

use Aura\Router\RouterContainer;
use Financas\ServiceContainerInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\ServerRequestFactory;

class RoutePlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $routerContainer = new RouterContainer();
        $map = $routerContainer->getMap();
        $matcher = $routerContainer->getMatcher();
        $generator = $routerContainer->getGenerator();
        $request = $this->getRequest();

        $container->add('routing',$map);
        $container->add('routing.matcher',$matcher);
        $container->add('routing.generator',$generator);
        $container->add(RequestInterface::class,$request);
        
        $container->addLazy('route', function(ContainerInterface $container){
            $matcher = $container->get('routing.matcher');
            $request = $container->get(RequestInterface::class);
            return $matcher->match($request);
        });
        
    }

    protected function getRequest():RequestInterface
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,$_GET,$_POST,$_COOKIE,$_FILES
        );
    }
}