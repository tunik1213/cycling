@extends('layout')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('areas.show',$district->area->id) }}">{{ $district->area->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $district->name }}</li>
  </ol>
</nav>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $district->name }} район</h2>
            </div>
            {{-- <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('districts.index') }}">← Назад</a>
            </div> --}}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Назва:</strong>
                {{ $district->name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Область:</strong>
                <a href="{{ route('areas.show',$district->area->id) }}">{{ $district->area->name }}</a>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>Герб:</strong>
            </div>
            <img src="{{ route('districts.image',$district->id) }}" alt="Герб {{$district->name}} район"/>

        </div>

        <div class="col-sm-6 col-xs-12">
            <strong>Пам'ятки:</strong>
            <div class="list-group">
            @foreach($district->sights as $s)
            <a href="{{ route('sights.show',$s->id) }}" class="list-group-item list-group-item-action">
                {{$s->name}}
            </a>
            @endforeach
            </div>
        </div>



    </div>
@endsection