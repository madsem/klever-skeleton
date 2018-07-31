<?php

namespace Klever\Middleware;

class ForceSslMiddleWare
{

    /**
     * Enforces all URIs of route to be secure
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     */
    function __invoke($request, $response, $next)
    {
        $uri = $request->getUri();
        if (request()->hasHeader('HTTP_X_FORWARDED_PROTO')
            && request()->getServerParam('HTTP_X_FORWARDED_PROTO') == 'http'
            || $uri->getScheme() !== 'https') {
            // Map http to https
            $httpsUrl = $uri->withScheme('https')->withPort(443)->__toString();

            // Redirect to HTTPS Url
            return $response->withRedirect($httpsUrl);
        }

        return $next($request, $response);
    }
}