@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Редагувати область</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('areas.index') }}">← Назад</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('areas.update',$area->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @method('PUT')
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Назва:</strong>
                    <input type="text" name="name" value="{{ $area->name }}" class="form-control" placeholder="{{ $area->name }}">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Змiнити герб:</strong>
                    <input type="file" name="area_image" id="area_image" class="form-control">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Зберегти</button>
            </div>
        </div>
    </form>
@endsection