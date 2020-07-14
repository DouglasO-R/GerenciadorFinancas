<?php

namespace Financas\Plugins;

use Financas\Models\BillPay;
use Financas\Models\BillReceive;
use Financas\Models\CategoryCost;
use Financas\Models\User;
use Financas\Repository\CategoryCostRepository;
use Financas\ServiceContainerInterface;
use Interop\Container\ContainerInterface;
use Financas\Repository\RepositoryFactory;
use Financas\Repository\StatementRepository;
use Illuminate\Database\Capsule\Manager as Capsule;

class DbPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $capsule = new Capsule();
        $config = include __DIR__ . '/../../config/db.php';
        $capsule->addConnection($config['development']);
        $capsule->bootEloquent();

        $container->add('repository.factory', new RepositoryFactory());

        $container->addLazy('category-cost.repository', function(){
            return new CategoryCostRepository();
        });

        $container->addLazy('bill-receive.repository', function (ContainerInterface $container) {
                return $container->get('repository.factory')->factory(BillReceive::class);
            });

        $container->addLazy('bill-pay.repository', function(ContainerInterface $container){
            return $container->get('repository.factory')->factory(BillPay::class);
        });

        $container->addLazy('user.repository',function(ContainerInterface $container){
            return $container->get('repository.factory')->factory(User::class);
        });

        $container->addLazy('statement.repository',function(){
            return new StatementRepository;
        });
        

    }

}