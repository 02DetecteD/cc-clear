<?php

namespace App\Tools;

use App\Models\Clients;
use Predis\Client as RedisClient;

class ApiHelper
{
    const LIVE_TIME_ACCESS_TOKEN = 600;
    /**
     * @return string
     * @throws \Exception
     */
    public static function create_refresh_token()
    {
        $token = self::create_token();
        $find = (new Clients())->where('refresh_token', $token)->first();
        if ($find) {
            return self::create_refresh_token();
        }
        return $token;
    }


    /**
     * Содзать Токен доступа
     * @return string
     * @throws \Exception
     */
    public static function create_access_token()
    {
        $redis = new RedisClient();
        $token = self::create_token();
        if ($redis->get($token)) {
            return self::create_access_token();
        }
        return $token;
    }

    /**
     * Сгенерировать токен
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function create_token($length = 32)
    {
        return bin2hex(random_bytes($length));
    }


    public static function save_access_to_redis($access_token, $phone, $live_time = self::LIVE_TIME_ACCESS_TOKEN)
    {
        $redis = new RedisClient();
        $redis->setex("access-token:{$access_token}", $live_time, $phone);
    }
}