<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Closure;

/**
 * @codeCoverageIgnore
 */
class RequestLogger
{
    protected $start_time = null;
    protected $end_time = null;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.debug')) {
            $this->start_time = microtime(true);
            \DB::enableQueryLog();
        }

        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        if (config('app.debug')) {
            $this->end_time = microtime(true);
            $query_logs = \DB::getQueryLog();

            $this->log($request, $response, $query_logs);
        }
    }

    protected function log(Request $request, $response, $query_logs)
    {
        $duration = $this->end_time - $this->start_time;
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();
        if (class_basename($response) === 'BinaryFileResponse') {
            $status = $response->getStatusCode();
        } else {
            $status = $response->status();
        }
        $parameters = json_encode($request->all());

        $query_log = [];
        foreach($query_logs as $query) {
            $query_log[] = 'Query: '.$query['query']."\nBindings: ".print_r($query['bindings'], true)."Time: ".$query['time']." ms\n";
        }

        $log = "\n-----------------------------------\n".
               "{$ip}: [{$status}] {$method} {$url} - {$duration}ms\n".
               $parameters."\n".
               implode("\n", $query_log).
               "-----------------------------------";

        Log::info($log);
    }
}
