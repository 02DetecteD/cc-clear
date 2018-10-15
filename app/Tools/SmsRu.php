<?php
namespace App\Tools;

require_once('libs/sms_ru/sms.ru.php');


class SmsRu {

    private $SmsRu;

    public function __construct()
    {
        $this->SmsRu = new \SMSRU(config('crystal.sms-ru.api-key'));
    }

    public function sendOneMessage($phone, string $message, bool $test = false)
    {
        $data = new \stdClass();
        if ($test) {
            $data->test = 1;
        }
        $data->to = $phone;
        $data->text = $message;
        $sms = $this->SmsRu->send_one($data);
        return ($sms->status == 'OK');
    }
}