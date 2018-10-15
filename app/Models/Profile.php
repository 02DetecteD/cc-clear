<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

/**
 * Class Profile
 *
 * @property int id
 * @property int client_id
 * @property int home_call
 * @property string address
 * @property string $avatar
 * @property string about
 * @property mixed info_to_array
 * @property mixed update_avatar
 * @property mixed get_avatar
 * @package App\Models
 * @mixin \Eloquent
 */
class Profile extends Model
{
    protected $table = 'client_profile';
    protected $fillable = [
        'client_id',
        'gender',
        'home_call',
        'address',
        'about',
        'avatar'
    ];

    const DEFAULT_AVATAR = '/storage/clients/avatars/default-avatar.jpg';
    const AVATAR_240x240 = '240x240';
    const AVATAR_165x165 = '165x165';
    const AVATAR_90x90 = '90x90';
    const AVATAR_SIZE = [
        self::AVATAR_240x240 => ['width' => 240, 'height' => 240],
        self::AVATAR_165x165 => ['width' => 165, 'height' => 165],
        self::AVATAR_90x90 => ['width' => 90, 'height' => 90]
    ];

    const PROFILE_COLUMN = [
        'gender' => '',
        'home_call' => 0,
        'address' => '',
        'about' => '',
        'first_name' => '',
        'surname' => ''
    ];

    /**
     * @var array
     * Колонки информации пользователя
     */
    const INFO_COLUMN = [
        'gender' => '',
        'home_call' => 0,
        'address' => '',
        'avatar' => self::DEFAULT_AVATAR,
        'about' => '',
        'first_name' => '',
        'surname' => ''
    ];


    /***
     * Получить аватар
     * @return array
     */
    public function get_avatars()
    {
        $result = [];
        if (empty($this->avatar)) {
            $size_key = array_keys(self::AVATAR_SIZE);
            return array_fill_keys($size_key, self::DEFAULT_AVATAR);
        }
        $path = 'storage/clients/' . $this->client_id . '/avatars/thumbnail/';
        foreach (self::AVATAR_SIZE as $size => $value) {
            $result[$size] = url($path . $this->avatar . "_{$value['width']}x{$value['height']}" . '.jpeg');
        }
        return $result;
    }


    /**
     * Обновить аватар
     * @param Clients $client
     * @param object $avatar
     * @return object
     */
    public function update_avatar(Clients $client, $avatar)
    {
        $storage = \Storage::disk('public');
        $path = 'clients/' . $client->id . '/avatars/thumbnail/';
        $storage->deleteDirectory($path);
        $image = Image::make($avatar->getRealPath())->encode('jpeg', 85);
        $file_name = md5(time() . str_random(20));
        foreach (self::AVATAR_SIZE as $size) {
            $width = $size['width'];
            $height = $size['height'];
            $resizeCanvas = $image->resize($width, $height);
            $resizeCanvas->stream();
            $put_file = $storage->put($path . $file_name . "_{$width}x{$height}" . '.jpeg', $resizeCanvas);
            if (!$put_file) {
                $storage->deleteDirectory($path);
                return (object)[
                    'success' => false,
                    'message' => 'Неудалось записать файл'
                ];
            }
        }
        return (object)[
            'success' => true,
            'url' => $file_name
        ];
    }
}
