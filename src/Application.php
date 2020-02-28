<?php
declare(strict_types=1);
namespace Financas;

use Financas\Plugins\PluginInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;

class Application
{
    private $serviceContainer;

    public function __construct(ServiceContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function getService(string $name)
    {
        return $this->serviceContainer->get($name);
    }

    public function addService(string $name, $service):void
    {
        if(is_callable($service)){
            $this->serviceContainer->addLazy($name,$service);
        } else {
            $this->serviceContainer->add($name,$service);
        }
    }

    public function plugin(PluginInterface $plugin):void
    {
        $plugin->register($this->serviceContainer);
    }

    public function get($path, $action, $name = null):Application
    {
        $routing = $this->getService('routing');
        $routing->get($name,$path,$action);
        return $this;
    }

    public function start():void
    {
        $route = $this->getService('route');
        /**@var ServerRequestInterface $request */
        $request = $this->getService(RequestInterface::class);

        if(!$route){
            echo 'Page not Found';
            exit;
        }

        foreach($route->attributes as $key => $value){
            $request = $request->withAttribute($key,$value);
        }

        $callable = $route->handler;
        $response = $callable($request);
        $this->emitResponse($response);
    }

    protected function emitResponse(ResponseInterface $response)
    {
        $emiter = new SapiEmitter();
        $emiter->emit($response);
    }
}