@php
    
@endphp

@extends('layout')
@section('content')

    <div class="row">
        <h2>
            {{$userList->title() ?? 'Зареєстрованi користувачi'}}
        </h2>
    </div>

    @include('user.list_partial',['userList'=>$userList])

@endsection


@section('javascript')
@endsection