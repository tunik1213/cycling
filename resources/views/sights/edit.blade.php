@php
    $moderator = Auth::user()->moderator ?? false;
    $lv = App\Models\SightVersion::lastVersion($sight);
@endphp

@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Редагувати локацію</h2>
        </div>
{{--         <div class="pull-right">
            <a href="{{ route('sights.index') }}">← Назад</a>
        </div> --}}
    </div>
</div>

<form action="{{ route('sights.update',$sight->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @if(isset($moderation_uri))
        <input type="hidden" name="moderation_uri" value="{{$moderation_uri}}" />
    @endif

    <strong>Вкажiть точку на картi або введiть координати нижче вручну</strong>
    <div id="map"></div>

    <div id="response-container">

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
            {!! $message !!}
        @endif

    </div>


    <div class="form-group row">
        <div class="col col-8">
            <strong>Координати (Ctrl+V):</strong>
            <div class="row">
                <div class="col col-6">
                    <input type="text" id="lat" name="lat" value="{{ old('lat') ?? $sight->lat }}" class="form-control @if($sight->lat != $orig->lat) changed-input @endif" placeholder="Широта" autocomplete="off">
                </div>
                <div class="col col-6">
                    <input type="text" id="lng" name="lng" value="{{ old('lng') ?? $sight->lng }}" class="form-control @if($sight->lng != $orig->lng) changed-input @endif" placeholder="Довгота" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="col col-4">
            <strong>Радiус, м</strong>&nbsp;&nbsp;<i class="fa-regular fa-circle-question" alt="Використовується для зарахування вiдвiдування" title="Використовується для зарахування вiдвiдування"></i>
            <input type="number" id="radius" name="radius" value="{{ old('radius') ?? $sight->radius }}" class="form-control @if($sight->radius != $orig->radius) changed-input @endif" placeholder="Радiус" autocomplete="off">
        </div>
    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Категорiя:</strong>
                <select id="category" name="category" class="form-select @if($sight->category != $orig->category) changed-input @endif" aria-label="Категорiя">
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
                <strong>Пiдкатегорiя: (необов'язково)</strong>
                <select disabled id="subcategory" name="subcategory" class="form-select @if($sight->subcategory != $orig->subcategory) changed-input @endif" aria-label="Пiдкатегорiя">
                </select>
            </div>

            <div class="form-group">
                <strong>Область:</strong>
                <input type="text" name="area" id="area" class="form-control @if($sight->area != $orig->area) changed-input @endif" placeholder="Почнiть набирати назву областi" value="{{ $sight->area->name ?? '' }}" autocomplete="off">
            </div>

            <input name="area_id" id="area_id" type="hidden" value="{{ $sight->area->id ?? '' }}" />

            <div class="form-group">
                <strong>Район:</strong>
                <input type="text" name="district" id="district" class="form-control @if($sight->district != $orig->district) changed-input @endif" placeholder="Почнiть набирати назву району" value="{{ $sight->district->name ?? '' }}" autocomplete="off">
            </div>

            <input name="district_id" id="district_id" type="hidden" value="{{ $sight->district->id ?? '' }}" />

            <div class="form-group">
                <strong>Населений пункт:</strong>
                <input type="text" name="locality" class="form-control @if($sight->locality != $orig->locality) changed-input @endif" placeholder="{{ old('locality') ?? $sight->locality}}" value="{{ $sight->locality }}" autocomplete="off">
            </div>

            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control @if($sight->name != $orig->name) changed-input @endif" placeholder="{{$sight->name}}" value="{{ old('name') ??  $sight->name }}" autocomplete="off">
            </div>

            <div class="form-group row">
                <div class="form-group">
                    <strong>Знiнити фото:</strong>
                    <input type="file" name="sight_image" id="sight_image" class="form-control @if($sight->image != $orig->image) changed-input @endif">
                </div>
                @if(!empty($sight->image))
                    <img class="sight-image" src="data:image/jpeg;base64,{{base64_encode($sight->image)}}"> 
                @else
                    <p>Фото вiдсутнє</p>
                @endif
            </div>

            <div class="col-12">
                <div class="form-group">
                    <strong>Лiцензiя (якщо потрiбно):</strong>
                    <input type="text" name="license" id="license" class="form-control @if($sight->license != $orig->license) changed-input @endif" value="{{old('license') ?? $sight->license}}" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <label for="description">Опис:</label>
                <textarea class="form-control @if($sight->description != $orig->description) changed-input @endif" name="description" id="description" rows="3">{{ old('description') ?? $sight->description}}</textarea>
            </div>
        </div>
        
    </div>

    <br />
    <div class="row">
        <p><button type="submit" class="btn btn-primary">Зберегти</button>
        @if($moderator && !empty($lv))
            <a href="{{route('sights.rollback',$sight->id)}}" class="btn btn-danger"><i class="fas fa-ban"></i> Скасувати правку</a>
        @endif
        </p>
    </div>
</form>



@endsection

@section('javascript')
    @include('sights.edit_js',['sight'=>$sight])
@endsection