<?php

namespace Financas\View;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class ViewRender implements ViewRenderInterface
{
    private $twigEnviroment;

    public function __construct(\Twig_Environment $twigEnviroment)
    {
        $this->twigEnviroment = $twigEnviroment;
    }
    public function render(string $template, array $context = []): ResponseInterface
    {
        $result = $this->twigEnviroment->render($template,$context);
        $response = new Response();
        $response->getBody()->write($result);
        return $response;
    }
}
