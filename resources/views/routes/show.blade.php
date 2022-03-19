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
    <div class="col-12 col-sm-4">
        @if(!empty($route->logo_image))
            <img class="route-logo-image" src="{{route('routes.image',['id'=>$route->id,'type'=>'logo'])}}" alt="Веломаршрут {{$route->name}}">
        @endif

         @if(!empty($route->license))
            <div class="lisence-text">{!! $route->license !!}</div>
        @endif
    </div>

    <div class="col-12 col-sm-8">
        <h2>{{ $route->name }}</h2>

        <div id="route-author">
            @if($route->user)
                <strong>Дода{{$route->user->gender('в','ла')}}: </strong>
                <a href="{{route('userProfile',$route->user->id)}}">{{ $route->user->fullname }}</a>
            @else
                <strong>Джерело: </strong>
            @endif
        </div>

        @if($route->canEdit())
        <div class="route-buttons">
            <a class="btn btn-primary" href="{{ route('routes.edit',$route->id) }}"><i class="fas fa-edit"></i> Редагувати </a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="add-link btn btn-secondary" href="{{route('sights.list',['routeAdd'=>$route->id])}}"><i class="fa fa-plus"></i>&nbsp;Додати&nbsp;точку </a>
        </div>
        @endif

    </div>

</div>

<div class="row">
    <p id="route-description">
        {!! $route->description !!}
    </p>
</div>
<div class="row">
    @if(!empty($route->map_image))
        <img class="route-map-image" src="{{route('routes.image',['id'=>$route->id,'type'=>'map'])}}" alt="Мапа веломаршруту {{$route->name}}">
    @endif
</div>

<div class="row">

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