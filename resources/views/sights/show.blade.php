@extends('layout')
@section('content')

@if($sight->district)
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('areas.show',$sight->district->area->id) }}">{{ $sight->district->area->name }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('districts.show',$sight->district->id) }}">{{ $sight->district->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $sight->name }}</li>
  </ol>
</nav>
@endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $sight->name }}</h2>
            </div>
{{--             <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('sights.index') }}">← Назад</a>
            </div> --}}
        </div>
    </div>


    @if($sight->user)
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Автор:</strong>
                <a href="{{route('userProfile',$sight->user->id)}}">{{ $sight->user->firstname }}</a>
            </div>
        </div>   
    @endif

    <div id="sight-images-container">
        <div>
            <img class="float-left" src="data:image/jpeg;base64,{{base64_encode($sight->image)}}" alt="Фото {{$sight->name}}">
        </div>
        <div>
            <img class="float-right" src="data:image/jpeg;base64,{{base64_encode($sight->map_image)}}" alt="Мапа {{$sight->name}}"> 
        </div>
    </div>  
        

        
        

@endsection