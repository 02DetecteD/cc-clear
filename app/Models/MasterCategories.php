<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestLog
 *
 * @property string method
 * @property integer client_id
 * @property integer category_id
 * @package App\Models
 * @mixin \Eloquent
 */
class MasterCategories extends Model
{
    protected $table = 'master_categories';
    protected $fillable = [
        'client_id',
        'category_id'
    ];

    /**
     * Получить список категорий
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function get_categories()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

}
