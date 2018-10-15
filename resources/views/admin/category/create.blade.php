@extends('admin.layout')


@section('container')
    <form action="{{route('admin.category.save')}}" method="post" class="card card-body" enctype="multipart/form-data">
        {{csrf_field()}}
        <h5 class="text-center mt-4 mb-2">Добавить категорию</h5>
        <div class="form-group">
            <label>Название категории</label>
            <input type="text" name="name" class="form-control" title="Название" placeholder="Введите название" required>
        </div>
        <div class="form-group">
            <label><b>Изображение</b></label>
            <br>
            <input type="file" name="image"  title="Название" placeholder="Изображение" required>
        </div>
        <button class="btn-success btn">
            Сохранить
        </button>
    </form>
@endsection