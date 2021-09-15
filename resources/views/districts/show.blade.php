@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $district->name }} район</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('districts.index') }}">← Назад</a>
            </div>
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

        <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>Герб:</strong>
        </div>
        <img src="{{ route('districts.image',$district->id) }}" alt="Герб {{$district->name}} район"/>

    </div>
@endsection