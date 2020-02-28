<?php

use Financas\Application;
use Financas\Models\CategoryCost;
use Financas\Plugins\DbPlugin;
use Financas\ServiceContainer;
use Financas\Plugins\RoutePlugin;
use Financas\Plugins\ViewPlugin;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());

$app->get('/category-costs',function(ServerRequestInterface $request) use($app){
    $view = $app->getService('view.render');
    $meuModel = new CategoryCost();
    $categories = $meuModel->all();    
    return $view->render('category-costs/list.html.twig', ['categories' => $categories]);
});

$app->get('/home/{name}',function(ServerRequestInterface $request){
    echo "ola";
    echo "<br>";
    echo $request->getAttribute('name');
});

$app->get('/oi/{name}',function(ServerRequestInterface $request) use($app){
    $view = $app->getService('view.render');
    return $view->render('test.html.twig', ['name' => $request->getAttribute('name')]);
});


$app->start();