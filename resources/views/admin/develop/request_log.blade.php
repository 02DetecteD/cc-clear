@extends('admin.layout')

@section('container')
    <div class="container">
        <div class="list-group">
            @if(!empty($data))
                @foreach($data as $item)

                    <div class="list-group-item list-group-item-action flex-column align-items-start active"
                         style="    margin-bottom: 40px;">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><b>#{{$item->id}} | URL: {{$item->url}} | Method: {{$item->method}}</b>
                            </h5>
                            <p>{{date('d.m.Y H:i:s', strtotime($item->created_at))}}</p>
                        </div>
                        <h5 class="text-center" style="border-bottom: 1px solid; padding-bottom: 10px;">Params</h5>
                        <div class="mb-1">
                            @php
                                $params = json_decode($item->params, true);
                            @endphp
                            @foreach($params as $key => $param)
                                <ul>
                                    <li><b>{{$key}}</b> <?= (is_array($param)) ? json_encode($param) : $param?></li>
                                </ul>
                            @endforeach
                        </div>
                        <h5 class="text-center" style="border-bottom: 1px solid; padding-bottom: 10px;">Headers</h5>
                        <div class="mb-1">
                            @php
                                $headers = json_decode($item->headers, true);

                           // echo "<pre>";var_dump($headers);exit();
                            @endphp
                            @foreach($headers as $key => $header)
                                <ul>
                                    <li><b>{{mb_strtoupper($key)}}</b>{{(!is_array($header)) ? $header : ''}}</li>
                                    @if(is_array($header))
                                        <ul>
                                            @foreach($header as $h)
                                                <li>{{$h}}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </ul>
                            @endforeach
                        </div>

                    </div>
                @endforeach
                {{$data->render()}}
            @else
                <h2 class="text-center">Данных пока нет.</h2>
            @endif
        </div>
    </div>
@endsection