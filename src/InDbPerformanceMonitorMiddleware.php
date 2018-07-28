<?php

namespace ASamir\InDbPerformanceMonitor;

use Closure;
use \ASamir\InDbPerformanceMonitor\LogRequests;

class InDbPerformanceMonitorMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        //
        if (LogRequests::isValidPathToLog($request->getPathInfo()))
            LogRequests::startInDbMonitor();

        return $next($request);
    }

    public function terminate($request, $response) {
        //
        if (LogRequests::isValidPathToLog($request->getPathInfo()))
        {
            $session_data = session()->all();
            unset($session_data['__asamir_token']);
            LogRequests::find($request->input('__asamir_request_id'))->update([
                'session_id' => session()->getId(),
                'session_data' => json_encode($session_data),
                'exec_time' => (microtime(true) - LARAVEL_START),
                'route_uri' => ((\Route::current()) ? \Route::current()->uri() : ''),
                'route_static_prefix' => ((\Route::current()) ? \Route::current()->getCompiled()->getStaticPrefix() : ''),
                'is_json_response' => ((json_decode($response->getContent()) != null) ? 1 : 0)
            ]);
        }
    }

}
