@extends('layout')
@section('content')

@if (Session::get('success'))
    <div class="alert alert-success">
        <p>{{Session::get('success')}}</p>
        <a class="link-secondary" href="{{route('sights.index')}}">← Повернутись до списку</a>
    </div>
@endif

<div class="row">
    <div class="col-sm-4 col-xs-12">
        <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->image)}}" alt="Фото {{$sight->name}}">
    </div>

    <div class="col-xl-4 col-sm-8 col-xs-12">
        <h2>{{ $sight->name }}</h2>

        {{$sight->categoryLink}}

        @if($sight->district)
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('areas.show',$sight->district->area->id) }}">{{ $sight->district->area->name }} область</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('districts.show',$sight->district->id) }}">{{ $sight->district->name }} район</a></li>
                    {{-- <li class="breadcrumb-item active" aria-current="page">{{ $sight->name }}</li> --}}
                </ol>
            </nav>
        @endif

        <div id="sight-author">
            <strong>Автор:</strong>
            @if($sight->user)
                <a href="{{route('userProfile',$sight->user->id)}}">{{ $sight->user->fullname }}</a>
            @else
                Google
            @endif
        </div>

        <p id="sight-description">
            {!! $sight->description !!}
        </p>
    </div>



    <div class="col-xl-4 col-md-12">
        <a href="{{$sight->gm_link()}}" target="_blank" rel="nofollow">
            <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
        </a>
    </div>

</div>
        
        

@endsection