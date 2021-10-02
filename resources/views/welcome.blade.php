@extends('layout')

@section('content')

    <h1>Назва сайту</h1>
    <h3>Коротка інформація про нашу діяльність</h3>

    @guest
        @include('user.login_btn')
    @else

        @include('sights.top',['user'=>Auth::user()])

    @endguest

@endsection