<?php

namespace Financas\Plugins;

use Financas\ServiceContainerInterface;
use Financas\View\ViewRender;
use Interop\Container\ContainerInterface;

class ViewPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy('twig', function(ContainerInterface $container){
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
            $twig = new \Twig_Environment($loader);
            return $twig;
        });

        $container->addLazy('view.render', function(ContainerInterface $container){
            $twigEnviroment = $container->get('twig');
            return new ViewRender($twigEnviroment);
        });
    }

}