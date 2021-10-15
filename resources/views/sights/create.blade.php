@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Додати нову пам'ятку</h2>
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

<form action="{{ route('sights.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Категорiя:</strong>
                <select name="category" class="form-select" aria-label="Категорiя">
                    @if(empty(old('category')))
                        <option selected value="0">Виберiть категорiю</option>
                    @endif

                    @foreach(\App\Models\SightCategory::all() as $cat)
                        @php
                            $selected = ($cat->id == old('category')) ? 'selected' : '';
                        @endphp
                        <option {{$selected}} value="{{$cat->id}}">
                           {{$cat->name}}
                        </option>

                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Район:</strong>
                <input type="text" name="district" id="district" class="form-control" placeholder="Почнiть набирати назву району" value="{{ old('district') }}" autocomplete="off">
            </div>
        </div>

        <input name="district_id" id="district_id" type="hidden" value="{{ old('district_id') }}" />

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control" placeholder="наприклад, Пейзажна алея" value="{{ old('name') }}" autocomplete="off">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group row col-xs-12 col-sm-9 col-md-6">
                <strong>Координати (Ctrl+V):</strong>
                <div class="col">
                    <input type="text" id="latitude" name="lat" value="{{ old('lat') }}" class="form-control" placeholder="Широта" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" id="longitude" name="lng" value="{{ old('lng') }}" class="form-control" placeholder="Довгота" autocomplete="off">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Фото:</strong>
                    <input type="file" name="sight_image" id="sight_image" class="form-control">
                </div>
                </div>

                <div class="form-group">
                    <label for="description">Опис:</label>
                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <br />
            <button type="submit" class="btn btn-primary">Зберегти</button>
            <br />
        </div>
    </div>
</form>
@endsection

@section('javascript')
    @include('sights.edit_js')
@endsection