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
     * URL to a named route with optional arguments
     *
     * @param $name
     * @param bool $absolute
     * @param array $args
     * @return string
     */
    function route($name, array $args = [], $absolute = true): string
    {
        $route = container()->get('router')
                            ->pathFor($name, $args);

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
    function view($name, $args = [])
    {
        $view = container()->get('view');

        try {

            return $view->render($name, $args);

        } catch (Exception $error) {

            // this only happens if a template wasn't found
            // i.e: if we would link to a non-existing view in twig
            // or another method that dynamically routes to a view at runtime
            return $view->render('errors/404.twig');
        }
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

if ( ! function_exists('validator')) {
    /**
     * Wrapper for Valitron\Validator
     *
     * @param array $data
     * @param array $fields
     * @return mixed
     */
    function validator($data = array(), $fields = array())
    {
        return container()
            ->get('validator')->withData($data, $fields);
    }
}

if ( ! function_exists('getHostNoSubDomain')) {
    /**
     * Get only domain.tld in case we have a sub domain
     * Explode host name and take last two elements from array
     * this means two-tier TLDs are not supported
     * To support this we would also need to compare against a list of all possible TLDs like .co.uk etc.
     *
     * @return string
     */
    function getHostNoSubDomain(): string
    {
        $host = request()->getUri()->getHost();
        return implode('.', array_slice(explode('.', $host), -2));
    }
}

if ( ! function_exists('vars')) {
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
    function vars($key, $default = null)
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
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return trim($value, '\'"');
    }
}