@extends('layout')

@section('container')
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('auth.login')}}" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <label for="exampleInputEmail1">Login</label>
                <input type="text" name="login" class="form-control" placeholder="Enter Login">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
    </div>
@endsection