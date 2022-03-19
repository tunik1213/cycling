@php
    $moderator = Auth::user()->moderator ?? false;
@endphp

@extends('layout')

@section('head')
    <link href='/draganddrop.css' rel='stylesheet' type='text/css'>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Редактор веломаршруту</h2>
        </div>
    </div>
</div>

<form action="{{ route('routes.update',$route->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')

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

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control" placeholder="наприклад, Менонiтська стежка" value="{{ old('name') ??  $route->name }}" autocomplete="off">
            </div>

            <div class="form-group row">
                <div class="form-group">
                    <strong>Титульне зображення (лого):</strong>
                    <input type="file" name="logo_image" id="logo_image" class="form-control">
                </div>
                @if(!empty($route->logo_image))
                    <img class="route-logo-image" src="data:image/jpeg;base64,{{base64_encode($route->logo_image)}}"> 
                @else
                    <p>Зображення вiдсутнє</p>
                @endif
            </div>

            <div class="form-group row">
                <div class="form-group">
                    <strong>Зображення мапи:</strong>
                    <input type="file" name="map_image" id="map_image" class="form-control">
                </div>
                @if(!empty($route->map_image))
                    <img class="route-map-image" src="data:image/jpeg;base64,{{base64_encode($route->map_image)}}"> 
                @else
                    <p>Зображення вiдсутнє</p>
                @endif
            </div>

            <div class="col-12">
                <div class="form-group">
                    <strong>Посилання на джерело (якщо потрiбно):</strong>
                    <input type="text" name="license" id="license" class="form-control" value="{{old('license') ?? $route->license}}" autocomplete="off">
                </div>
            </div>

            <br />

            <div id="route-list-edit">
                <h3>Точки маршрута</h3>
                <div id="route-list-edit-locations" class=" list-group">
                    @foreach($route->sights()->get() as $s)
                    <div class="list-group-item list-group-item-action" sight="{{$s->id}}">
                        <div class="image" ><img src="data:image/jpeg;base64,{{base64_encode($s->image)}}"/></div>
                        <div class="name"><a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a></div>
                        <a class="remove" title="Прибрати" href="#"><i class="fa-solid fa-xmark"></i></a>
                    </div>
                    @endforeach
                </div>
                <button type="submit" id="add-button" class="btn btn-secondary"><i class="fa fa-plus"></i> Додати точку</button>
            </div>

            <input type="hidden" name="sights" id="sights">
            <input type="hidden" name="redirect" id="redirect">

        </div>

        <input type="hidden" name="finished" id="finished">

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <label for="description">Опис:</label>
                <textarea class="form-control" name="description" id="description" rows="3">{{ old('description') ?? $route->description}}</textarea>
            </div>
        </div>
        
    </div>

    <br />
    <div class="row">
        <p><button type="submit" finish="0" class="btn btn-primary">Зберегти</button>
        @if(!$route->finished)
            <button type="submit" finish="1" class="btn btn-warning"><i class="fas fa-flag-checkered"></i> Опублiкувати маршрут</button>
        @endif
        </p>
    </div>
</form>



@endsection

@section('javascript')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src='/draganddrop.js' type='text/javascript'></script>

<script type="text/javascript">
    $('#route-list-edit a.remove').on('click',removeSightFromRoute);
    $('#route-list-edit-locations').sortable({container: '#route-list-edit-locations', nodes: '.list-group-item'});
    $('button[type="submit"]').on('click',save);



    function save(e) {
        $ids = '';
        $('#route-list-edit-locations .list-group-item').each(function(){
            $ids = $ids + $(this).attr('sight')+',';
        });
        $('input#sights').val($ids);
        
        $('input#finished').val($(this).attr('finish'));

        if($(this).is('#add-button')) $('input#redirect').val('{{route('sights.list',['routeAdd'=>$route->id])}}');
    }

    function removeSightFromRoute(e) {
        $(this).closest('.list-group-item').remove();
        e.preventDefault();
    }

    tinymce.init({
        selector: '#description',
        language: 'uk',
        plugins: 'link, emoticons, lists, charmap, paste, textcolor',
        paste_as_text: true,
        toolbar: 'undo redo | bold italic removeformat | forecolor backcolor | bullist numlist | charmap emoticons link image media',
        menubar: false,
        setup: function(editor) {
            editor.on('init', fillEditorContent);
        },
        contextmenu: false,
        browser_spellcheck: true,
        relative_urls: false,
        height : '350'
    });

    function fillEditorContent(e) {
        this.setContent(`{!! str_replace('`','\'',$route->description ?? old('description') ?? '' ) !!}`);
    }

</script>
@endsection