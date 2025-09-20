<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class JsonMiddleware
{
    protected $factory;
    public function __construct(ResponseFactory $factory)
    {
        $this->factory = $factory;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $original = $response->getData(true);
            $status = $response->status();
            $success = $status >= 200 && $status < 300;

            // Avoid overwriting if already defined
            $original = ['success' => $success, 'status' => $status ] + $original;

            $response->setData($original);
        }

        return $response;
    }
}
