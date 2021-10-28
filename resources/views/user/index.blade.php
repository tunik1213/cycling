@php
    
@endphp

@extends('layout')
@section('content')

    <div class="row">
        <h2>
            {{$list_title ?? 'Зареєстрованi користувачi'}}
        </h2>
    </div>

    @include('user.list_partial',['users'=>$users])

@endsection


@section('javascript')
@endsection