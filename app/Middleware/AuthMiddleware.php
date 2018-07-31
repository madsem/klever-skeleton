<?php

namespace Klever\Middleware;

class AuthMiddleware
{

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    function __invoke($request, $response, $next)
    {
        if ( ! container()->get('auth')->verifySession()) {

            session()->flush();

            return redirect(route('auth.login'));
        }

        return $next($request, $response);
    }
}