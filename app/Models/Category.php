<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 *
 * @property string name
 * @property string image
 * @package App\Models
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $table = 'category';
    protected $fillable = [
        'name',
        'image',
    ];

}
