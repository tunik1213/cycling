@php
    $moderator = Auth::user()->moderator ?? false;
@endphp

@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: Веломаршрут {{$route->name}}</title>
@endsection

@section('content')

@if (Session::get('success'))
    <div class="alert alert-success">
        <p>{!!Session::get('success')!!}</p>
    </div>
@endif

@if(!$route->isPublic())
    <div class="alert alert-warning">
        <p>Наразi маршрут очiкує схвалення модератора</p>
    </div>
@endif

<div class="row">
    <div class="col-lg-4 col-12">
        @if(!empty($route->image))
            <img class="route-image" src="{{route('routes.image',$route->id)}}" alt="Веломаршрут {{$route->name}}">
            @if(!empty($route->license))
                <div class="lisence-text">{!! $route->license !!}</div>
            @endif
        @else
            <span>Фото вiдсутнє</span>
        @endif
    </div>

    <div class="col-lg-8 col-xs-12">
        <h2>{{ $route->name }}</h2>

        <div id="route-author">
            @if($route->user)
                <strong>Дода{{$route->user->gender('в','ла')}}: </strong>
                <a href="{{route('userProfile',$route->user->id)}}">{{ $route->user->fullname }}</a>
            @else
                <strong>Джерело: </strong>
            @endif
        </div>

        <p id="route-description">
            {!! $route->description !!}
        </p>

        @if($route->canEdit())
        <div class="row route-buttons">
            <p>
                <a class="btn btn-primary" href="{{ route('routes.edit',$route->id) }}"><i class="fas fa-edit"></i> Редагувати </a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="add-link" href="{{route('sights.list',['routeAdd'=>$route->id])}}"><i class="fa fa-plus"></i> Додати точку </a>
            </p>
        </div>
        @endif
    </div>

    <div class="container">
        TODO топ мандрiвникiв
        {{-- @include('user.top',['userList'=>$topUsers,'list_title'=>'Топ мандрiвникiв']) --}}
    </div>

</div>
        
        

@endsection


@section('javascript')

   <script type="text/javascript">

   </script>

@endsection