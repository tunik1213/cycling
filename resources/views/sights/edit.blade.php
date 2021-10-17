@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Редагувати пам'ятку</h2>
        </div>
        <div class="pull-right">
            <a href="{{ route('sights.index') }}">← Назад</a>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-warning">
            <p>{!! $message !!}</p>
        </div>
    @endif

<form action="{{ route('sights.update',$sight->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Категорiя:</strong>
                <select name="category" class="form-select" aria-label="Категорiя">
                    @if(empty($sight->category))
                        <option selected value="0">Виберiть категорiю</option>
                    @endif

                    @foreach(\App\Models\SightCategory::all() as $cat)
                        @php
                            $selected = ($cat->id == ($sight->category->id ?? null)) ? 'selected' : '';
                        @endphp
                        <option {{$selected}} value="{{$cat->id}}">
                           {{$cat->name}}
                        </option>

                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <strong>Район:</strong>
                <input type="text" name="district" id="district" class="form-control" placeholder="Почнiть набирати назву району" value="{{ $sight->district->name ?? '' }}" autocomplete="off">
            </div>

            <input name="district_id" id="district_id" type="hidden" value="{{ $sight->district->id ?? '' }}" />

            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control" placeholder="{{$sight->name}}" value="{{ $sight->name }}" autocomplete="off">
            </div>

            <div class="form-group row">
                <strong>Координати (Ctrl+V):</strong>
                <div class="col">
                    <input type="text" id="latitude" name="lat" value="{{ $sight->lat }}" class="form-control" placeholder="Широта" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" id="longitude" name="lng" value="{{ $sight->lng }}" class="form-control" placeholder="Довгота" autocomplete="off">
                </div>

                <div class="form-group">
                    <strong>Знiнити фото:</strong>
                    <input type="file" name="sight_image" id="sight_image" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <label for="description">Опис:</label>
                <textarea class="form-control" name="description" id="description" rows="3">{{$sight->description}}</textarea>
            </div>
        </div>
        
    </div>

    <br />
    <div class="row">
        <p><button type="submit" class="btn btn-primary">Зберегти</button></p>
    </div>
</form>
@endsection

@section('javascript')
    @include('sights.edit_js',['sight'=>$sight])
@endsection