<?php

namespace App\Http\Middleware;

use App\Models\Clients;
use App\Tools\ApiHelper;
use Closure;
use Predis;

class AuthToken
{

    const TYPE_ACCESS = 'Access';
    const TYPE_REFRESH = 'Refresh';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $authorization = $request->header('Authorization');

        if (!$authorization) {
            return response()->json([
                'success' => false,
                'message' => __('api.authorization_header_error')
            ], 400);
        }
        $explode = explode(' ', trim($authorization), 2);
        $type = (isset($explode[0]) && !empty($explode[0])) ? $explode[0] : null;
        $token = (isset($explode[1]) && !empty($explode[1])) ? $explode[1] : null;
        if (!$type || !$token) {
            return response()->json([
                'success' => false,
                'message' => __('api.invalid_tokens')
            ], 400);
        }
        $redis = new Predis\Client();
        switch ($type) {
            case self::TYPE_ACCESS :
                if (!$redis->get("access-token:{$token}")) {
                    return response()->json([
                        'success' => false,
                        'message' => __('api.unauthorized')
                    ], 401);
                }
                return $next($request);
            case self::TYPE_REFRESH :
                $find_token = (new Clients())->find_refresh_token($token);

                if (!$find_token) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Refresh Token'
                    ], 400);
                }
                $new_refresh_token = ApiHelper::create_refresh_token();
                $new_access_token = ApiHelper::create_access_token();
                $update = $find_token->update_refresh_token($new_refresh_token);
                if (!$update) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed update refresh token'
                    ], 400);
                }
                ApiHelper::save_access_to_redis($new_access_token, $find_token->phone);
                $request->headers->set('Authorization', 'Access ' . $new_access_token);
                $response = $next($request);
                $response->headers->set('Access-Token', $new_access_token);
                $response->headers->set('Refresh-Token', $new_refresh_token);
                return $response;
            default :
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid type tokens.'
                ], 400);
        }
    }
}
