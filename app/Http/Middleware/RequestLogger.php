<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger {
    const SENSIBLE_FIELDS = [
        'password',
        'password_confirmation',
        'c_password',
        'current_password',
        'new_password',
        'new_password_confirmation',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if ($this->shouldSkipLogging($request)) {
            return $next($request);
        }

        $startTime = microtime(true);

        $response = $next($request);

        $this->logRequest($request, $response, $startTime);

        return $response;
    }

    private function shouldSkipLogging(Request $request): bool {
        return $request->isMethod('GET') && env('LOG_GET_REQUESTS', false) == false;
    }

    private function logRequest(Request $request, Response $response, float $startTime): void {
        RequestLog::create([
            'ref_user' => $request->user()?->id,
            'method' => $request->method(),
            'path' => $request->path(),
            'query' => json_encode($request->query()),
            'body' => json_encode($this->filterSensibleData($request->all())),
            'headers' => json_encode($request->headers->all()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_status' => $response->getStatusCode(),
            'execution_time' => $this->calculateExecutionTime($startTime),
            'response' => $response->getContent(),
        ]);
    }

    private function filterSensibleData(array $data): array {
        foreach ($data as $key => $value) {
            if (in_array($key, self::SENSIBLE_FIELDS)) {
                $data[$key] = '***';
            }
        }
        return $data;
    }

    private function calculateExecutionTime(float $startTime): float {
        return (microtime(true) - $startTime) * 1000;
    }
}
