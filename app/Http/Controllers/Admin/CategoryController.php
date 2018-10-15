<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list()
    {
        $sql = Category::paginate(15);
        return view('admin.category.list', ['data' => $sql]);
    }


    public function create()
    {
        return view('admin.category.create');
    }

    public function save(Request $request)
    {
        $data = $request->all();
        $validate = \Validator::make($data, [
            'name' => 'required',
            'image' => 'required|max:10000|mimes:png,jpg,jpeg'
        ]);
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        $storage = \Storage::disk('public');
        $save = $storage->put('category/images', $request->file('image'));
        if (!$save) {
            return back()->withErrors(['error_save' => 'Ошибка сохранения файла'])->withInput();
        }
        $category = new \App\Models\Category();
        $category->name = $data['name'];
        $category->image = $save;
        if(!$category->save()){
            return back()->withErrors(['error_save' => 'Ошибка добавления катагории'])->withInput();
        }
        return redirect(route('admin.category.list'));
    }
}