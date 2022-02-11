@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sight->name}}</title>
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
       integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
       crossorigin=""/>
@endsection

@section('content')

@if (Session::get('success'))
    <div class="alert alert-success">
        <p>{!!Session::get('success')!!}</p>
    </div>
@endif

@if(!$sight->isPublic())
    <div class="alert alert-warning"><p>
        Наразi пам'ятка очiкує схвалення модератора

        @if($sight->author == Auth::user())
            
        @else
            
        @endif
    </div></p>
@endif

<div class="row">
    <div class="col-lg-4 col-12">
        @if(!empty($sight->image))
            <img class="sight-image" src="{{route('sights.image',$sight->id)}}" alt="Фото {{$sight->name}}">
            @if(!empty($sight->license))
                <div class="lisence-text">{!! $sight->license !!}</div>
            @endif
        @else
            <span>Фото вiдсутнє</span>
        @endif

        <div class="desktop" id="desktop-map">
            {{-- <a href="{{$sight->gm_link()}}" target="_blank" rel="nofollow">
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
            </a> --}}
        </div>
    </div>

    <div class="col-lg-8 col-xs-12">
        <h2>{{ $sight->name }}</h2>

        {{$sight->categoryLink}}


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">{{ $sight->area->link }}</li>
                @if(!empty($sight->district))
                    <li class="breadcrumb-item"><a href="{{ route('districts.show',$sight->district->id) }}">{{ $sight->district->name }} район</a></li>
                @endif
                @if(!empty($sight->locality))
                    <li class="breadcrumb-item active" aria-current="page">{{ $sight->locality }}</li>
                @endif
            </ol>
        </nav>

        <div id="sight-author">
            @if($sight->user)
                <strong>Дода{{$sight->user->gender('в','ла')}}: </strong>
                <a href="{{route('userProfile',$sight->user->id)}}">{{ $sight->user->fullname }}</a>
            @else
                <strong>Джерело: </strong>Google
            @endif
        </div>

        <p id="sight-description">
            {!! $sight->description !!}
        </p>

        @if(Auth::user()->moderator ?? false)
        <div id="sight-radius">
            <strong>Радiус: </strong>{{$sight->radius}}м
        </div>
        <div class="row sight-buttons">
            <div class="col">
                <a class="btn btn-primary" href="{{ route('sights.edit',$sight->id) }}"><i class="fas fa-edit"></i> Редагувати</a>
            </div>
            <div class="col">
                <form action="{{ route('sights.destroy',$sight->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Видалити</button>
                </form>
            </div>
        </div>
        @endif
    </div>



    <div class="col-lg-4 col-12 mobile" id="mobile-map">
        {{-- <a href="{{$sight->gm_link()}}" target="_blank" rel="nofollow">
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
        </a> --}}
    </div>

    <div class="container">
        @include('user.top',['userList'=>$topUsers,'list_title'=>'Топ мандрiвникiв'])
    </div>

</div>
        
        

@endsection


@section('javascript')
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

   <script type="text/javascript">
        var latlng = [{{$sight->lat}}, {{$sight->lng}}];
        var mapSelector = 'desktop-map';
        if(!$('#'+mapSelector).is(':visible')) {
            mapSelector = 'mobile-map';
        }
        var map = L.map(mapSelector).setView(latlng, 13);
        //L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/1/1/0?access_token={{env('MAPBOX_TOKEN')}}', {
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
        }).addTo(map);

        var marker = L.marker(latlng).addTo(map);
   </script>

@endsection