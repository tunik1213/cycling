@extends('layout')
@section('content')

    <div class="container info-block">
        <h2>
            {{$userList->title() ?? 'Зареєстрованi користувачi'}}
        </h2>
        
        <div class="info-block-body">

            @include('user.list_partial',['userList'=>$userList])
            
        </div>
    </div>

@endsection
