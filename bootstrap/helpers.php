<?php

/**
 * Global Application Helpers
 */

if ( ! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @return \Slim\App
     */
    function app(): Slim\App
    {
        return \Klever\App\Wrapper::getInstance();
    }
}

if ( ! function_exists('container')) {
    /**
     * Return container instance
     *
     * @return \Psr\Container\ContainerInterface
     */
    function container(): \Psr\Container\ContainerInterface
    {
        return app()->getContainer();
    }
}

if ( ! function_exists('request')) {
    /**
     * Return Request Object from App
     *
     * @return \Slim\Http\Request
     */
    function request(): \Slim\Http\Request
    {
        return container()->get('request');
    }
}

if ( ! function_exists('redirect')) {
    /**
     * Use Response Object from container
     * to generate a redirect response
     *
     * @param $to
     * @param int $status
     * @return \Slim\Http\Response
     */
    function redirect($to, $status = 301): \Slim\Http\Response
    {
        return container()->get('response')
                          ->withRedirect($to, $status);
    }
}

if ( ! function_exists('route')) {
    /**
     * Generate absolute or relative
     * URL to a named route
     *
     * @param $name
     * @param bool $absolute
     * @return string
     */
    function route($name, $absolute = true): string
    {
        $route = container()->get('router')
                            ->pathFor($name);

        $scheme = request()->getUri()->getScheme();
        $host = request()->getUri()->getHost();

        return $absolute ? $scheme . '://' . $host . $route : $route;
    }
}

if ( ! function_exists('base_path')) {
    /**
     * Return path to app root
     *
     * @param string $path
     * @return string
     */
    function base_path($path = ''): string
    {
        return dirname(__DIR__) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if ( ! function_exists('view')) {
    /**
     * Retrieve Klever/Views/View instance from
     * container and trigger render method.
     *
     * @param string $name
     * @param array $args
     * @return \Slim\Http\Response
     */
    function view($name, $args = []): \Slim\Http\Response
    {
        $view = container()->get('view');

        return $view->render($name, $args);
    }
}

if ( ! function_exists('cache')) {
    /**
     * Retrieve Cache instance from container
     * and make methods available globally
     *
     * @return \Klever\Cache\Cache
     */
    function cache(): \Klever\Cache\Cache
    {
        return container()->get('cache');
    }
}

if ( ! function_exists('config')) {
    /**
     * Retrieve Config instance from container
     * and make methods available globally
     *
     * @return \Klever\Config\Config
     */
    function config(): \Klever\Config\Config
    {
        return container()->get('config');
    }
}

if ( ! function_exists('session')) {
    /**
     * Retrieve Session instance from container
     * and make methods available globally
     *
     * @return \Klever\Storage\Session
     */
    function session(): \Klever\Storage\Session
    {
        return container()->get('session');
    }
}

if ( ! function_exists('cookie')) {
    /**
     * Retrieve Cookie instance from container
     * and make methods available globally
     *
     * @return \Klever\Storage\Cookie
     */
    function cookie(): \Klever\Storage\Cookie
    {
        return container()->get('cookie');
    }
}

if ( ! function_exists('message')) {
    /**
     * Retrieve Slim\Flash instance from container
     * and add message
     *
     * @param $key
     * @param $message
     * @return mixed
     */
    function message($key, $message)
    {
        return container()->get('flash')
                          ->addMessage($key, $message);
    }
}

if ( ! function_exists('env')) {
    /**
     * Return requested key from environment
     * or return default value
     *
     * @param $key
     * @param null $default
     * @return array|bool|false|null|string
     *
     * @link https://github.com/illuminate/support/blob/master/helpers.php
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}