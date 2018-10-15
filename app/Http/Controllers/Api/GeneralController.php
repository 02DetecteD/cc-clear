<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\General\SearchServices;
use App\Models\Category;
use App\Models\Clients;
use App\Models\MasterServices;
use App\Tools\Api;

/** @resource General */
class GeneralController extends Controller
{
    /**
     * Получить список категорий
     * @return mixed
     */
    public function categories_get()
    {
        $category = (new Category())->get(['name', 'image', 'id']);
        return Api::response_ok($category->toArray());
    }

    /**
     * Получить список стран
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries_get()
    {
        $countries = [
            'Россия' => '+7'
        ];
        return Api::response_ok($countries);
    }

    /**
     * Поиск по мастерам
     * @param SearchServices $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search_services(SearchServices $request)
    {
        $data = $request->all(['name']);
        $search = (new MasterServices())
            ->where('name', 'LIKE', '%' . $data['name'] . "%")->get();
        return Api::response_ok($search->toArray());
    }
}