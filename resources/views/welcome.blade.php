@extends('layout')

@section('content')

    <h1 class="title-main">Сайт для веломандрівників</h1>
    <h4 class="title-secondary">Лосем можеш і не бути, але туристом бути зобов'язаний!</h3>

    @guest
        @include('user.login_btn')
    @else

        @include('sights.top',['user'=>Auth::user()])

    @endguest

@endsection