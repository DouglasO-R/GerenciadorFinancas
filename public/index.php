<?php

use Financas\Application;
use Financas\ServiceContainer;
use Financas\Plugins\RoutePlugin;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());

$app->get('/',function(ServerRequestInterface $request){
    $response = new Response();
    $response->getBody()->write('Ola Mundo');
    return $response;
});

$app->get('/home/{name}',function(ServerRequestInterface $request){
    echo "ola";
    echo "<br>";
    echo $request->getAttribute('name');
});

$app->start();