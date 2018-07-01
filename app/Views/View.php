<?php


namespace Klever\Views;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class View
{

    /**
     * @var Twig \League\Container\Container
     */
    protected $view;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var RequestInterface
     */
    protected $request;

    function __construct(Twig $view, RequestInterface $request, ResponseInterface $response)
    {
        $this->view = $view;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * Render Twig views passed back from controllers
     *
     * @param $name
     * @param array $args
     * @return ResponseInterface
     */
    function render($name, $args = [])
    {
        return $this->view->render($this->response, $name, $args);
    }
}