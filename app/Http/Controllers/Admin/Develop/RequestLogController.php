<?php

namespace App\Http\Controllers\Admin\Develop;

use App\Http\Controllers\Controller;
use App\Models\RequestLog;

class RequestLogController extends Controller
{
    public function list()
    {
        $data = (new RequestLog())->orderBy('id', 'desc')->paginate(10);
        return view('admin.develop.request_log', ['data' => $data]);
    }
}