<?php

use Psr\Http\Message\ServerRequestInterface;

$app
    ->get(
        '/login', function () use ($app) {
            $view = $app->getService('view.render'); 
            return $view->render('auth/login.html.twig');
        }, 'auth.show_login_form'
    )
    ->post(
        '/login', function (ServerRequestInterface $request) use ($app) {
            $auth = $app->getService('auth');
            $view = $app->getService('view.render'); 
            $data = $request->getParsedBody();
            $result = $auth->login($data);
            if(!$result) {
                return $view->render('auth/login.html.twig');
            }
            return $app->route('category-costs.list');
        }, 'auth.login'
    )
    ->get(
        '/logout', function () use ($app) {
            $app->getService('auth')->logout(); 
            return $app->route('auth.show_login_form');
        }, 'auth.logout'
    );

$app->before(
    function () use ($app) {
        $route = $app->getService('route');
        $auth = $app->getService('auth');
        $routeWhiteList = [
        'auth.show_login_form',
        'auth.login',
        'users.new',
        'index'        
        ];
        if(!in_array($route->name, $routeWhiteList) && !$auth->check()) {
            return $app->route('auth.show_login_form');
        }
    }
);