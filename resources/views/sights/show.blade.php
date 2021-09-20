@extends('layout')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('areas.show',$sight->district->area->id) }}">{{ $sight->district->area->name }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('districts.show',$sight->district->id) }}">{{ $sight->district->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $sight->name }}</li>
  </ol>
</nav>

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

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Назва:</strong>
                {{ $sight->name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>Фото:</strong>
        </div>
        <img src="{{ route('sights.image',$sight->id) }}" alt="Фото {{$sight->name}}"/>
        
        

    </div>
@endsection