<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\UpdateProfile;
use App\Http\Requests\Api\Master;
use App\Models\Category;
use App\Models\Clients;
use App\Models\MasterCategories;
use App\Models\MasterServices;
use App\Models\Profile;
use App\Tools\Api;
use App\Tools\ApiHelper;
use Illuminate\Http\Request;

/** @resource Clients */
class ProfileController extends Controller
{
    /**
     * Получение профиля клиента
     * @return array
     */
    public function get()
    {
        $client = Api::client();
        $profile = $client->profile_to_array(Profile::PROFILE_COLUMN);
        return Api::response_ok($profile);
    }

    /**
     * Получение данных пользователя
     */
    public function get_info()
    {
        $client = Api::client();
        $profile = $client->profile_to_array(Profile::INFO_COLUMN);
        return Api::response_ok($profile);
    }

    /**
     * Привязать категорию к пользователю
     * @param Master\AddCategory $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_category(Master\AddCategory $request)
    {
        $data = $request->all(['category_id']);
        $client = Api::client();
        $master_category = new MasterCategories();
        $find = (new Category())->where('id', $data['category_id'])->first();
        if (!$find) {
            return Api::response_fail("Данной категории не найдено.");
        }
        $find_unique = (new MasterCategories())
            ->where('category_id', $data['category_id'])
            ->where('client_id', $client->id)
            ->first();
        if ($find_unique) {
            return Api::response_fail("Данной категории уже добавлена добавлена.");
        }
        $master_category->client_id = $client->id;
        $master_category->category_id = $data['category_id'];
        $master_category->save();
        return Api::response_ok();
    }

    /**
     * Добавить услугу мастера
     * @param Master\AddServices $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_services(Master\AddServices $request)
    {
        $data = $request->all([
            'category_id',
            'name',
            'price',
            'description'
        ]);
        $model = (new MasterServices());
        $client = Api::client();
        $model->category_id = $data['category_id'];
        $model->client_id = $client->id;
        $model->name = $data['name'];
        $model->price = $data['price'];
        $model->description = $data['description'];
        if (!$model->save()) {
            return Api::response_fail("Ошибка сохарнения сервиса.");
        }
        return Api::response_ok();
    }


    /**
     * Получить услуги предоставляесые мастером
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_services()
    {
        $client = Api::client();
        $model = (new MasterServices())
            ->where('client_id', $client->id)
            ->get();
        $result= [];
        foreach ($model as $item) {
            $result[] = [
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'category_id' => $item->get_category->id,
                'category_name' => $item->get_category->name
            ];
        }
        return Api::response_ok($result);
    }


    /**
     * Получить список категори пользователя
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_categories()
    {
        $client = Api::client();
        $sql = (new MasterCategories())
            ->where('client_id', $client->id)
            ->get();
        $result = [];
        foreach ($sql as $item) {
            $result[] = [
                'name' => $item->get_categories->name,
                'id' => $item->get_categories->id
            ];
        }
        return Api::response_ok($result);
    }


    /**
     * Переключение между ролями клиентом и мастером.
     */
    public function toggle_role()
    {
        $client = Api::client();
        $client->role = ($client->role == Clients::ROLE_CLIENT) ? Clients::ROLE_MASTER : Clients::ROLE_CLIENT;
        if (!$client->update()) {
            return response()->json([
                'success' => false,
                'message' => 'Error, update role.'
            ], 400);
        }
        return Api::response_ok();
    }

    /**
     * Обновление данных профиля
     * @param UpdateProfile $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfile $request)
    {
        $data = $request->all([
            'gender',
            'home_call',
            'address',
            'about',
            'avatar'
        ]);
        try {
            $model = new Profile();
            $client = Api::client();
            if (!is_null($data['avatar'])) {
                $update_avatar = $model->update_avatar($client, $data['avatar']);
                if ($update_avatar->success == true) {
                    $data['avatar'] = $update_avatar->url;
                } else {
                    throw new \Exception(($update_avatar->message) ?? "Неизвестная ошибка");
                }
            }
            $model->updateOrCreate([
                'client_id' => $client->id
            ], $data);
        } catch (\Exception $e) {
            return Api::response_fail($e->getMessage());
        }
        return Api::response_ok();
    }

}