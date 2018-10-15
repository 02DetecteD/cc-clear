<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkingSchedule
 *
 * @property string date
 * @property int client_id
 * @property int hour
 * @package App\Models
 * @mixin \Eloquent
 */
class WorkingSchedule extends Model
{
    const HOURS_WORKING = [
        '0', '1', '2', '3', '4',
        '5', '6', '7', '8',
        '9', '10', '11', '12',
        '13', '14', '15', '16',
        '17', '18', '19', '20',
        '21', '22', '23',
    ];
    protected $id;
    protected $table = 'working_schedule';
    protected $fillable = [
        'client_id',
        'date',
        'hour',

    ];


    /**
     * Получить робочее время
     * @param $client_id
     * @param $date
     * @return array
     */
    public function get_working($client_id, $date)
    {
        $find = (new WorkingSchedule)
            ->select('hour')
            ->where("client_id", $client_id)
            ->where('date', $date)
            ->get();

        $find = (!empty($find)) ? $find->toArray() : [];
        return $this->create_graph($find);
    }

    /**
     * Установить час который не работает мастер.
     * @param $client_id
     * @param $date
     * @param $hour
     * @return bool
     */
    public function set_not_working($client_id, $date, $hour)
    {
        $find = $this
            ->where("client_id", $client_id)
            ->where('date', $date)
            ->where('hour', $hour)
            ->first();
        if (!$find) {
            $create = (new WorkingSchedule());
            $create->client_id = $client_id;
            $create->date = $date;
            $create->hour = $hour;
            return $create->save();
        }
        return true;
    }

    /**
     * @param array $working
     * @return array
     */
    private function create_graph(array $working)
    {
        $result = array_fill_keys(self::HOURS_WORKING, true);
        foreach ($working as $item) {
            $h = $item['hour'];
            $result[$h] = false;
        }
        return $result;
    }


}
