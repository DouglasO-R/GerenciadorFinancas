<?php

use Psr\Http\Message\ServerRequestInterface;

$app
    ->get('/bill-pays',function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $repository = $app->getService('bill-pay.repository');
        $auth = $app->getService('auth');
        $bills = $repository->findByField('user_id', $auth->user()->getId());
        return $view->render('bill-pays/list.html.twig', ['bills' => $bills]);
    },'bill-pays.list')
    ->get('/bill-pays/new', function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $auth = $app->getService('auth');
        $categoryRepository = $app->getService('category-cost.repository');
        $categories = $categoryRepository->findByField('user_id', $auth->user()->getId());
        return $view->render('bill-pays/create.html.twig', ['categories' => $categories]);
    }, 'bill-pays.new')
    ->post('/bill-pays/store', function(ServerRequestInterface $request) use($app){
        $data = $request->getParsedBody();
        $repository = $app->getService('bill-pay.repository');
        $categoryRepository = $app->getService('category-cost.repository');
        $auth = $app->getService('auth');
        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $data['category_cost_id'] = $categoryRepository->findOneBy([
            'id' => $data['category_cost_id'],
            'user_id' => $auth->user()->getId()
        ])->id;
        $repository->create($data);
        return $app->route('bill-pays.list');
    }, 'bill-pays.store')
    ->get('/bill-pays/{id}/edit', function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $id = $request->getAttribute('id');
        $repository = $app->getService('bill-pay.repository');
        $auth = $app->getService('auth');
        $bill = $repository->findOneBy([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ]);
        $categoryRepository = $app->getService('category-cost.repository');
        $categories = $categoryRepository->findByField('user_id', $auth->user()->getId());

        return $view->render('bill-pays/edit.html.twig', [
            'bill' => $bill,
            'categories' => $categories
        ]);
    }, 'bill-pays.edit')
    ->post('/bill-pays/{id}/update', function(ServerRequestInterface $request) use($app){
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $repository = $app->getService('bill-pay.repository');
        $categoryRepository = $app->getService('category-cost.repository');
        $auth = $app->getService('auth');
        $data['user_id'] = $auth->user()->getId();
        $data['date_launch'] = dateParse($data['date_launch']);
        $data['value'] = numberParse($data['value']);
        $data['category_cost_id'] = $categoryRepository->findOneBy([
            'id' => $data['category_cost_id'],
            'user_id' => $auth->user()->getId()
        ])->id;
        $repository->update([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ],$data);
        return $app->route('bill-pays.list');
    }, 'bill-pays.update')
    ->get('/bill-pays/{id}/show', function(ServerRequestInterface $request) use($app){
        $view = $app->getService('view.render');
        $id = $request->getAttribute('id');
        $repository = $app->getService('bill-pay.repository');
        $auth = $app->getService('auth');
        $bill = $repository->findOneBy([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ]);
        return $view->render('bill-pays/show.html.twig', ['bill' => $bill]);
    }, 'bill-pays.show')
    ->get('/bill-pays/{id}/delete', function(ServerRequestInterface $request) use($app){
        $id = $request->getAttribute('id');
        $repository = $app->getService('bill-pay.repository');
        $auth = $app->getService('auth');
        $repository->delete([
            'id' => $id,
            'user_id' => $auth->user()->getId()
        ]);
        return $app->route('bill-pays.list');
    }, 'bill-pays.delete');
