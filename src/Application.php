<?php
declare(strict_types=1);
namespace Financas;

use Financas\Plugins\PluginInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\RedirectResponse;

class Application
{
    private $serviceContainer;
    private $befores = [];

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

    public function post($path, $action, $name = null):Application
    {
        $routing = $this->getService('routing');
        $routing->post($name,$path,$action);
        return $this;
    }

    public function redirect($path)
    {
        return new RedirectResponse($path);
    }

    public function route(string $name, array $params = [])
    {
        $generator = $this->getService('routing.generator');
        $path =  $generator->generate($name, $params);           
        return $this->redirect($path);
    }

    public function before(callable $callback): Application
    {
        array_push($this->befores,$callback);
        return $this;
    }

    protected function runBefores(): ?ResponseInterface
    {
        foreach($this->befores as $callback)
        {
            $result = $callback($this->getService(RequestInterface::class));
            if($result instanceof ResponseInterface){
                return $result;
            }
        }
        return null;
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

        $result = $this->runBefores();
        if ($result) {
            $this->emitResponse($result);
            return;
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