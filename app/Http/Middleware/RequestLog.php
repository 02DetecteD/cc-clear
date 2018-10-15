<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RequestLog as RequestLogModel;


class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $is_request_log = env('IS_REQUEST_LOG', 'false');
        if ($is_request_log) {
            $url = $request->getPathInfo();
            $params = $request->all();
            unset($params[$url]);
            $method = $request->method();
            $headers = $request->headers->all();

            $log = new RequestLogModel();
            $log->url = $url;
            $log->params = json_encode($params);
            $log->method = $method;
            $log->headers = json_encode($headers);
            $log->save();
        }
        return $next($request);
    }
}
