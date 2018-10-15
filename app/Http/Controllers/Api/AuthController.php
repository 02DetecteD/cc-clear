<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Register;
use App\Http\Requests\Api\Auth\SendSms;
use App\Models\Clients;
use App\Tools\ApiHelper;
use App\Tools\SmsRu;
use Predis;


/** @resource Authentication */
class AuthController extends Controller
{
    /**
     * Отправка, смс с кодом подтверждения пользователю.
     * @param SendSms $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_sms(SendSms $request)
    {
        $data = $request->all(['phone']);
        $code = rand(10000, 99999);
        $send_code = (new SmsRu)->sendOneMessage($data['phone'], $code);
        if (!$send_code) {
            return response()->json([
                'success' => false,
                'message' => __('api.error_sending_code')
            ], 400);
        }
        $redis = new Predis\Client();
        $redis->setex("phone:{$data['phone']}", 300, $code);
        return response()->json([
            'success' => true
        ]);
    }


    /**
     * Регистрация по номеру телефона и полученному коду
     * Возвращает Access и Refresh token
     * @param Register $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Register $request)
    {
        $data = $request->all(['code', 'phone']);
        $redis = new Predis\Client();
        $find_code = $redis->get("phone:{$data['phone']}");
        if (is_null($find_code) || $find_code != $data['code']) {
            return response()->json([
                'success' => false,
                'message' => __('api.invalid_registration_code')
            ], 400);
        }
        $refresh_token = bin2hex(random_bytes(32));
        $access_token = bin2hex(random_bytes(32));
        (new Clients())->updateOrCreate([
            'phone' => $data['phone']
        ], [
            'phone' => $data['phone'],
            'refresh_token' => $refresh_token
        ]);
        $redis->del(["phone:{$data['phone']}"]);
        ApiHelper::save_access_to_redis($access_token, $data['phone']);
        return response()
            ->json(['success' => true])
            ->header('Access-Token', $access_token)
            ->header('Refresh-Token', $refresh_token);
    }

}