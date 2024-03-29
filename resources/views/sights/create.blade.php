@php
    $moderator = Auth::user()->moderator ?? false;
@endphp


@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Додати нову локацію</h2>
        </div>
{{--         <div class="pull-right">
            <a href="{{ route('sights.index') }}">← Назад</a>
        </div> --}}
    </div>
</div>


<form action="{{ route('sights.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

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
                    <input type="text" id="lat" name="lat" value="{{ old('lat') }}" class="form-control" placeholder="Широта" autocomplete="off">
                </div>
                <div class="col col-6">
                    <input type="text" id="lng" name="lng" value="{{ old('lng') }}" class="form-control" placeholder="Довгота" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col col-4">
            <strong>Радiус, м</strong>&nbsp;&nbsp;<i class="fa-regular fa-circle-question desktop" data-toggle="tooltip" title="Використовується для зарахування вiдвiдування"></i>
            <input type="number" id="radius" name="radius" value="{{ old('radius') ?? 25 }}" class="form-control" placeholder="Радiус" autocomplete="off">
        </div>
    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6">

            @if($moderator)

                <div class="form-group">
                    <strong>Категорiя:</strong>
                    <select id="category" name="category" class="form-select" aria-label="Категорiя">
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

                <div class="form-group">
                    <strong>Пiдкатегорiя (необов'язково):</strong>
                    <select disabled id="subcategory" name="subcategory" class="form-select" aria-label="Пiдкатегорiя">
                        @if(empty(old('subcategory')))
                            <option selected value="0">Виберiть пiдкатегорiю</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <strong>Область:</strong>
                    <input type="text" name="area" id="area" class="form-control" placeholder="Почнiть набирати назву областi" value="{{ old('area') }}" autocomplete="off">
                </div>

                <input name="area_id" id="area_id" type="hidden" value="{{ old('area_id') }}" />

                <div class="form-group">
                    <strong>Район:</strong>
                    <input type="text" name="district" id="district" class="form-control" placeholder="Почнiть набирати назву району" value="{{ old('district') }}" autocomplete="off">
                </div>

                <input name="district_id" id="district_id" type="hidden" value="{{ old('district_id') }}" />

                <div class="form-group">
                    <strong>Класнicть:</strong>
                    <select id="classiness" name="classiness" class="form-select" aria-label="Класнiсть">
                        @if(empty(old('classiness')))
                            <option selected value="0">Виберiть класнiсть</option>
                        @endif

                        @foreach(\App\Models\Sight::classinessList() as $i=>$c)
                            @php
                                $selected = old('classiness')==$i ? 'selected' : '';
                            @endphp
                            <option {{$selected}} value="{{$i}}">{{$c}}</option>
                        @endforeach
                    </select>
                </div>

            @endif

            <div class="form-group">
                <strong>Населений пункт (необов'язково):</strong>
                <input type="text" name="locality" class="form-control" placeholder="наприклад, с. Широке" value="{{ old('locality') }}" autocomplete="off">
            </div>

            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control" placeholder="наприклад, Пейзажна алея" value="{{ old('name') }}" autocomplete="off">
            </div>

            <div class="form-group">

                <div class="col-12">
                    <div class="form-group">
                        <strong>Фото:</strong>
                        <input type="file" name="sight_image" id="sight_image" class="form-control" value="{{old('sight_image')}}">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <strong>Лiцензiя (якщо потрiбно):</strong>
                        <input type="text" name="license" id="license" class="form-control" value="{{ old('license') }}" autocomplete="off">
                    </div>
                </div>

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <label for="description">Опис:</label>
                <textarea class="form-control" name="description" id="description"></textarea>
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
    @include('sights.edit_js')
@endsection