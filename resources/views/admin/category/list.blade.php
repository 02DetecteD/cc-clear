@extends('admin.layout')


@section('container')
    <div class="card card-body">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">изображение</th>
                <th scope="col">Название</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <th scope="row">{{$item->id}}</th>
                    <td>
                        <img src="{{url('storage/'. $item->image)}}" height="auto" width="140px" alt="" class="img-thumbnail">
                    </td>
                    <td>{{$item->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection