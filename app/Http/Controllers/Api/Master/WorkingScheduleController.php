<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\GetWorkingSchedule;
use App\Http\Requests\Api\Master\SetWorkingSchedule;
use App\Models\WorkingSchedule;
use App\Tools\Api;
use Carbon\Carbon;


/** @resource WorkingSchedule */
class WorkingScheduleController extends Controller
{

    /**
     * Получить график работы
     * @param GetWorkingSchedule $request
     * @return array
     */
    public function get(GetWorkingSchedule $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $client = Api::client();
        $working = (new WorkingSchedule)->get_working($client->id, $date);
        return Api::response_ok($working);
    }


    /**
     * Установить данный час как не рабочий.
     * @param SetWorkingSchedule $request
     * @return bool
     */
    public function set(SetWorkingSchedule $request)
    {
        $date = $request->get('date');
        $hour = $request->get('hour');
        $client = Api::client();
        try {
            (new WorkingSchedule)->set_not_working($client->id, $date, $hour);
            return Api::response_ok();
        } catch (\Exception $e) {
            return Api::response_fail('Error set working');
        }
    }


    /**
     * Установить данный час как рабочий
     * @param SetWorkingSchedule $request
     * @return bool
     * @throws \Exception
     */
    public function unset(SetWorkingSchedule $request)
    {
        $date = $request->get('date');
        $hour = $request->get('hour');
        $client = Api::client();
        $find = (new WorkingSchedule())
            ->where('client_id', $client->id)
            ->where('date', $date)
            ->where('hour', $hour)
            ->first();
        if ($find) {
            $find->delete();
        }
        return Api::response_ok();
    }

}