@section('head')
    <title>{{env('APP_NAME')}}: сайт веломандрівника</title>
@endsection


@extends('layout')

@section('banner')

@guest
    <div id="banner-bg">
        <div id="banner-layout">
            <div>
                <span id="title-1">Сайт веломандрівника</span>
            </div>
        </div>

        <div class="container">

            <div id="main-title">
                <div class="">
                    <span id="title-3">Проєкт створено для веломандрівників Україною</span>
                </div>
                <span id="main-title-text">Velocian</span>
            </div>
            
            <div>
                <span id="title-2" class="bg-dim">Лосем можна і не бути, а туристом - обов'язково!</span>
            </div>

            @include('user.login_btn')

        </div>
    </div>

    <div id="banner-space">
    </div>

@endguest


@endsection

@section('content')

    

    @guest
        
    @else

        @include('sights.top',['sightList'=>$topSights])

    @endguest

    @include('user.top',['userList'=>$topUsers])


    @include('user.top',['userList'=>$topAuthors])

@endsection


@guest
        
@endguest