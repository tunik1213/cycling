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

        <div class="col-sm-6 col-xs-12">
            <img src="{{ route('districts.image',$district->id) }}" alt="Герб {{$district->name}} район"/>
        </div>

    </div>
    <br/>
    <div class="row">
        @include('sights.top',['sightList'=>$topSights])
    </div>

    <div class="row">
        @include('user.top',['userList'=>$topUsers])
    </div>
@endsection