<?php

namespace Psr7Middlewares\Middleware;

use Psr7Middlewares\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware to block robots search engine.
 */
class Robots
{
    const HEADER = 'X-Robots-Tag';

    /**
     * Execute the middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getUri()->getPath() === '/robots.txt') {
            $body = Middleware::createStream();
            $body->write("User-Agent: *\nDisallow: /");

            return $response->withBody($body)->withHeader('Content-Type', 'text/plain');
        }

        $response = $next($request, $response);

        return $response->withHeader(self::HEADER, 'noindex, nofollow, noarchive');
    }
}