<?php

use Psr\Http\Message\ServerRequestInterface;

$app
    ->get(
        '/users', function (ServerRequestInterface $request) use ($app) {
            $view = $app->getService('view.render');
            $repository = $app->getService('user.repository');
            $users = $repository->all();    
            return $view->render('users/list.html.twig', ['users' => $users]);
        }, 'users.list'
    )
    ->get(
        '/users/new', function (ServerRequestInterface $request) use ($app) {
            $view = $app->getService('view.render');   
            return $view->render('users/create.html.twig');
        }, 'users.new'
    )
    ->post(
        '/users/store', function (ServerRequestInterface $request) use ($app) {
            $data = $request->getParsedBody();
            $repository = $app->getService('user.repository');
            $auth = $app->getService('auth');
            $data['password'] = $auth->hasPassword($data['password']);
            $repository->create($data);
            return $app->route('users.list');
        }, 'users.store'
    )
    ->get(
        '/users/{id}/edit', function (ServerRequestInterface $request) use ($app) {
            $view = $app->getService('view.render');   
            $id = $request->getAttribute('id');
            $repository = $app->getService('user.repository');
            $user = $repository->find($id);
            return $view->render('users/edit.html.twig', ['user' => $user]);
        }, 'users.edit'
    )
    ->post(
        '/users/{id}/update', function (ServerRequestInterface $request) use ($app) {  
            $id = $request->getAttribute('id');
            $data = $request->getParsedBody();
            $repository = $app->getService('user.repository');

            if(isset($data['password'])){
                unset($data['password']);
            }
            $repository->update($id, $data);
            return $app->route('users.list');
        }, 'users.update'
    )
    ->get(
        '/users/{id}/show', function (ServerRequestInterface $request) use ($app) {  
            $view = $app->getService('view.render');   
            $id = $request->getAttribute('id');
            $repository = $app->getService('user.repository');
            $user = $repository->find($id);
            return $view->render('users/show.html.twig', ['user' => $user]);
        }, 'users.show'
    )
    ->get(
        '/users/{id}/delete', function (ServerRequestInterface $request) use ($app) {   
            $id = $request->getAttribute('id');
            $repository = $app->getService('user.repository');
            $repository->delete($id);        
            return $app->route('users.list');
        }, 'users.delete'
    );