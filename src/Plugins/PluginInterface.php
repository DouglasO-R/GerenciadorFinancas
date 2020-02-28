<?php

namespace Financas\Plugins;

use Financas\ServiceContainerInterface;

interface PluginInterface
{
    public function register(ServiceContainerInterface $container);
}