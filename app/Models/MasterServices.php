<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestLog
 *
 * @property string method
 * @property integer category_id
 * @property string name
 * @property integer price
 * @property string description
 * @property integer client_id
 * @package App\Models
 * @mixin \Eloquent
 */
class MasterServices extends Model
{
    protected $table = 'master_services';
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
        'client_id'
    ];

    /**
     * Получить список категорий
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function get_category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

}
