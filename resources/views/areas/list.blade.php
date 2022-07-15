@section('head')
    <title>{{env('APP_NAME')}}: {{$areaList->title()}}</title>
@endsection


@extends('layout')
@section('content')

    <div class="container info-block">
        <h2>
            {!!$areaList->h1() ?? 'Список областей'!!}
        </h2>
            
        @include('areas.list_partial',['areaList'=>$areaList])
    </div>

@endsection
