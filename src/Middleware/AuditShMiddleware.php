<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel\Middleware;

use AuditSh\ClientPHPLaravel\Facade\AuditSh;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * Class AuditShMiddleware
 * @package AuditSh\ClientPHPLaravel\Middleware
 */
final class AuditShMiddleware implements TerminableInterface
{
    public function handle($request, \Closure $next)
    {
        AuditSh::startTransaction();

        AuditSh::currentTransaction()->addContext(
            'request',
            [
                'ip'         => $request->getClientIp(),
                'user_agent' => $request->userAgent(),
                'method'     => $request->getMethod(),
                'url'        => $request->fullUrl(),
            ]
        );
        $response =  $next($request);

        return $response;
    }

    public function terminate(Request $request, Response $response)
    {
        AuditSh::currentTransaction()->addContext(
            'response',
            [
                'status_code' => $response->getStatusCode(),
                'size'        => $this->responseSize($response),
            ]
        );
        AuditSh::currentTransaction()->addContext(
            'performance',
            [
                'memory' => memory_get_peak_usage(),
                'time'   => microtime(true) - LARAVEL_START,
            ]
        );
    }

    private function responseSize(Response $response)
    {
        if ($size = $response->headers->get('content-length')) {
            return $size;
        }

        return strlen($response->getContent());
    }
}
