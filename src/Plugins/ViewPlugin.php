<?php

namespace Financas\Plugins;

use Financas\ServiceContainerInterface;
use Financas\View\Twig\TwigGlobals;
use Financas\View\ViewRender;
use Interop\Container\ContainerInterface;
use Twig_SimpleFunction;

class ViewPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy('twig', function(ContainerInterface $container){
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
            $twig = new \Twig_Environment($loader);

            $auth = $container->get('auth');

            $generator = $container->get('routing.generator');
            $twig->addExtension(new TwigGlobals($auth));
            $twig->addFunction(new Twig_SimpleFunction('route', function(string $name, array $params = []) use($generator){
                return $generator->generate($name, $params);
            }));
            return $twig;
        });

        $container->addLazy('view.render', function(ContainerInterface $container){
            $twigEnviroment = $container->get('twig');
            return new ViewRender($twigEnviroment);
        });
    }

}