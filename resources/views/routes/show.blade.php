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
    </div>

    <div class="col-12 col-sm-8">
        <h2>{{ $route->name }}</h2>

        <div>
            <strong>{{$route->areas()}}</strong>
        </div>

        <div id="distance">
            <strong>Дистанцiя: </strong>{{$route->distance}}км
        </div>

        <div id="locations">
            <strong>Локацiї: </strong>{{$route->sights->count()}}
        </div>

        <div id="grunt_percent">
            <strong>Ґрунт/асфальт: </strong>{{$route->grunt_percent}}/{{100-$route->grunt_percent}}
        </div>
        
        <div id="route-author">
            @if($route->user)
                <strong>Дода{{$route->user->gender('в','ла')}}: </strong>
                <a href="{{route('userProfile',$route->user->id)}}">{{ $route->user->fullname }}</a>
            @else
                <strong>Джерело: </strong>
            @endif
        </div>

        @if(!empty($route->license))
            {!! $route->license !!}
        @endif

        @if($route->canEdit())
        <div class="route-buttons">
            <a class="btn btn-primary" href="{{ route('routes.edit',$route->id) }}"><i class="fas fa-edit"></i> Редагувати </a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="add-link btn btn-secondary" href="{{route('sights.list',['routeAdd'=>$route->id])}}"><i class="fa fa-plus"></i>&nbsp;Додати&nbsp;локацію </a>
        </div>
        @endif

    </div>

</div>

<div class="row" id="route-description">
    {!! $route->description !!}
</div>

@if($route->sights()->count()>0)
<div class="list-group">
@foreach($route->sights()->get() as $s)
    <div class="list-group-item list-group-item-action">
        <div class="image" ><img src="data:image/jpeg;base64,{{base64_encode($s->image)}}"/></div>
        <div class="name">
            <div class="row">
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
            </div>
            <div class="row">
                @if(!empty($s->locality))
                    <span>{{ $s->locality }}</span>
                @endif
                @if(!empty($s->district))
                    <span>{{ $s->district->name }} р-н </span>
                @endif
                @if(!empty($s->area))
                    <span>{{ $s->area->displayName }}</span>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>
@endif

<div class="row">
    @if(!empty($route->map_image))
        <img class="route-map-image" src="{{route('routes.image',['id'=>$route->id,'type'=>'map'])}}" alt="Мапа веломаршруту {{$route->name}}">
    @endif
</div>

<br />
<div id="comments-container" object-id="{{$route->id}}" object-type="route">
    @include('comments.list',['comments'=>$route->comments0()])
</div>

<div class="row">

    <div class="container">
        @include('user.top',['userList'=>$topUsers,'list_title'=>'Топ мандрiвникiв'])
    </div>

</div>
        
        

@endsection


@section('javascript')

   <script type="text/javascript">

   </script>

@endsection