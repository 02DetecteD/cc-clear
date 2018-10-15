<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestLog
 *
 * @property string method
 * @property string url
 * @property string params
 * @property string headers
 * @package App\Models
 * @mixin \Eloquent
 */
class RequestLog extends Model
{
    protected $table = 'request_log';
    protected $fillable = [
        'method',
        'url',
        'params',
        'headers'
    ];
}
