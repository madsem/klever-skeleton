<?php

namespace Klever\Views;


use Slim\Views\Twig;

class ViewFactory
{

    protected $view;
    protected $paths;
    protected $settings;

    function __construct(array $paths, array $settings)
    {
        $this->paths = $paths;
        $this->settings = $settings;
    }

    /**
     * instantiate twig engine
     *
     * @return Twig
     */
    function getEngine()
    {
        return new Twig($this->paths, $this->settings);
    }

    /**
     * \Illuminate\Pagination\Paginator uses this
     *
     * @param $view
     * @param array $data
     * @return $this
     */
    function make($view, $data = [])
    {
        $this->view = $this->getEngine()->fetch($view, $data);

        return $this;
    }

    /**
     * \Illuminate\Pagination\Paginator uses this
     *
     * @return mixed
     */
    function render()
    {
        return $this->view;
    }
}