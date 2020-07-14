<?php

namespace Financas\Plugins;

use Financas\Auth\Auth;
use Financas\Auth\JasnyAuth;
use Financas\ServiceContainerInterface;
use Interop\Container\ContainerInterface;


class AuthPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {    
        $container->addLazy('jasny.auth',function(ContainerInterface $container){
            return new JasnyAuth($container->get('user.repository'));
        });   

        $container->addLazy('auth',function(ContainerInterface $container){
            return new Auth($container->get('jasny.auth'));
        });        

    }

}