<?php

use Psr\Http\Message\ServerRequestInterface;

$app
    ->get('/bill-receives',function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $repository = $app->getService('bill-receive.repository');
        $auth = $app->getService('auth');
        $bills = $repository->findByField('user_id', $auth->user()->getId());
        return $view->render('bill-receives/list.html.twig', ['bills' => $bills]);
    },'bill-receives.list')
    ->get('/bill-receives/new', function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        return $view->render('bill-receives/create.html.twig');
    }, 'bill-receives.new')
    ->post('/bill-receives/store', function(ServerRequestInterface $request) use($app){
        $data = $request->getParsedBody();
        $repository = $app->getService('bill-receive.repository');
        $auth = $app->getService('auth');
        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $repository->create($data);
        return $app->route('bill-receives.list');
    }, 'bill-receives.store')
    ->get('/bill-receives/{id}/edit', function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $id = $request->getAttribute('id');
        $repository = $app->getService('bill-receive.repository');
        $auth = $app->getService('auth');
        $bill = $repository->findOneBy([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ]);
        return $view->render('bill-receives/edit.html.twig', ['bill' => $bill]);
    }, 'bill-receives.edit')
    ->post('/bill-receives/{id}/update', function(ServerRequestInterface $request) use($app){
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $repository = $app->getService('bill-receive.repository');
        $auth = $app->getService('auth');
        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $repository->update([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ],$data);
        return $app->route('bill-receives.list');
    }, 'bill-receives.update')
    ->get('/bill-receives/{id}/show', function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $id = $request->getAttribute('id');
        $repository = $app->getService('bill-receive.repository');
        $auth = $app->getService('auth');
        $bill = $repository->findOneBy([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ]);
        return $view->render('bill-receives/show.html.twig', ['bill' => $bill]);
    }, 'bill-receives.show')
    ->get('/bill-receives/{id}/delete', function(ServerRequestInterface $request) use($app){
        $id = $request->getAttribute('id');
        $repository = $app->getService('bill-receive.repository');
        $auth = $app->getService('auth');
        $repository->delete([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ]);
        return $app->route('bill-receives.list');
    }, 'bill-receives.delete');
