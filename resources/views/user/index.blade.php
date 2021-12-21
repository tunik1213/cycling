@extends('layout')
@section('content')

    <div class="container info-block">
        <h2>
            {{$userList->title() ?? 'Зареєстрованi користувачi'}}
        </h2>
            
        @include('user.list_partial',['userList'=>$userList])
    </div>

@endsection
