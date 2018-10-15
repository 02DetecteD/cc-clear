<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Clients
 *
 * @property integer $id
 * @property string phone
 * @property mixed get_profile
 * @property int role
 * @property mixed refresh_token
 * @package App\Models
 * @mixin \Eloquent
 */
class Clients extends Model
{

    const ROLE_CLIENT = 1;
    const ROLE_MASTER = 2;

    protected $table = 'clients';
    protected $fillable = [
        'phone',
        'refresh_token'
    ];

    protected $hidden = [
        'refresh_token'
    ];


    /**
     * Найти рефреш токен пользователя
     * @param $token
     * @return Clients|Model|null|object
     */
    public function find_refresh_token($token)
    {
        return $this
            ->where('refresh_token', $token)
            ->first();
    }


    /**
     * Установить новый Refresh Token
     * @param $new_token
     * @return bool
     */
    public function update_refresh_token($new_token)
    {
        $this->refresh_token = $new_token;
        return $this->update();
    }

    /**
     * Получение профиля клиента
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function get_profile()
    {
        return $this->hasOne('App\Models\Profile', 'client_id', 'id');
    }

    /**
     * Сформировать ответ для api
     * @param $attributes
     * @return array
     */
    public function profile_to_array($attributes)
    {
        $profile = $this->get_profile;
        $profile_attributes = $attributes;
        $result = [
            'phone' => $this->phone
        ];
        $result = array_merge($result, $profile_attributes);

        if (!$profile) {
            return $result;
        }
        foreach ($profile->toArray() as $key => $item) {
            if (isset($result[$key])) {
                if (!is_null($item)) {
                    if ($key == 'avatar') {
                        $result[$key] = $profile->get_avatars();
                    } else {
                        $result[$key] = $item;
                    }
                }
            }
            continue;
        }
        return $result;
    }
}
