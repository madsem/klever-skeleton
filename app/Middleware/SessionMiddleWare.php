<?php

namespace Klever\Middleware;

class SessionMiddleWare
{

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    function __invoke($request, $response, $next)
    {
        // start session
        session()->start();

        return $next($request, $response);
    }
}