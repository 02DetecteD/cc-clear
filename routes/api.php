<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'request.log']], function () {

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('send-sms', 'Api\AuthController@send_sms');
        Route::post('register', 'Api\AuthController@register');
    });

    Route::group(['middleware' => 'auth.token'], function () {

        Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
            Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
                Route::get('get', 'Api\Client\ProfileController@get');
                Route::get('get-info', 'Api\Client\ProfileController@get_info');
                Route::post('toggle-role', 'Api\Client\ProfileController@toggle_role');
                Route::post('update', 'Api\Client\ProfileController@update');
            });
        });

        Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
            Route::group(['prefix' => 'working-schedule', 'as' => 'working-schedule.'], function () {
                Route::get('get', 'Api\Master\WorkingScheduleController@get');
                Route::post('set', 'Api\Master\WorkingScheduleController@set');
                Route::post('unset', 'Api\Master\WorkingScheduleController@unset');
            });
            Route::post('add-category', 'Api\Client\ProfileController@add_category');
            Route::get('get-categories', 'Api\Client\ProfileController@get_categories');
            Route::get('get-services', 'Api\Client\ProfileController@get_services');
            Route::post('add-services', 'Api\Client\ProfileController@add_services');
        });
    });

    Route::group(['prefix' => 'general', 'as' => 'general.'], function () {
        Route::get('category/get', 'Api\GeneralController@categories_get');
        Route::get('countries/get', 'Api\GeneralController@countries_get');
        Route::post('services/search', 'Api\GeneralController@search_services');
    });

});