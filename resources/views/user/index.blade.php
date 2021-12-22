@section('head')
    <title>{{env('APP_NAME')}}: {{$userList->title()}}</title>
@endsection


@extends('layout')
@section('content')

    <div class="container info-block">
        <h2>
            {!!$userList->h1() ?? 'Зареєстрованi користувачi'!!}
        </h2>
            
        @include('user.list_partial',['userList'=>$userList])
    </div>

@endsection
