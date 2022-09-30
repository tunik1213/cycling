@section('head')
    <title>{{env('APP_NAME')}}: {{$districtList->title()}}</title>
@endsection


@extends('layout')
@section('content')

    <div class="container info-block">
        <h2>
            {!!$districtList->h1() ?? 'Список районiв'!!}
        </h2>
            
        @include('districts.list_partial',['districtList'=>$districtList])
    </div>

@endsection
