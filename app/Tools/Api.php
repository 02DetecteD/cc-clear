<?php

namespace App\Tools;

use App\Models\Clients;
use Predis\Client as RedisClient;

class Api
{
    /**
     * Получить клиента по access token
     * @return Clients|\Illuminate\Database\Eloquent\Model|null|object
     */
    public static function client()
    {
        $authorization = request()->header('Authorization');
        $explode = explode(' ', trim($authorization), 2);
        $redis = new RedisClient();
        $phone = $redis->get("access-token:{$explode[1]}");
        return (new Clients())->where('phone', $phone)->first();
    }

    public static function response_fail(string $message, array $data = [])
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 400);
    }

    public static function response_ok(array $data = [])
    {
        return response()->json([
            'success' => true,
            'result' => $data
        ], 200);
    }
}