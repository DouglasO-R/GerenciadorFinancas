<?php

$serviceContainer = new \Financas\ServiceContainer();
$app = new \Financas\Application($serviceContainer);


$app->plugin(new \Financas\Plugins\DbPlugin());
$app->plugin(new \Financas\Plugins\AuthPlugin());

return $app;