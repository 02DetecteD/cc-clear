<?php

use \App\User;

$role = [
    'admin' => User::ROLE_ADMIN
];


Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('sing-in', 'Auth\LoginController@login')->name('login');
    Route::get('login-form', 'Auth\LoginController@showLoginForm')->name('login-form');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => "role:{$role['admin']}"], function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('list', 'Admin\CategoryController@list')->name('list');
        Route::get('create', 'Admin\CategoryController@create')->name('create');
        Route::post('save', 'Admin\CategoryController@save')->name('save');
    });


    Route::group(['prefix' => 'develop', 'as' => 'develop.'], function () {
        Route::get('request-log', 'Admin\Develop\RequestLogController@list')->name('request-log');

    });
});