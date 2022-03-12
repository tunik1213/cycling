@php
    $moderator = Auth::user()->moderator ?? false;
@endphp

@extends('layout')
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
    @method('PUT')

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
                    <strong>Знiнити фото:</strong>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                @if(!empty($route->image))
                    <img class="route-image" src="data:image/jpeg;base64,{{base64_encode($route->image)}}"> 
                @else
                    <p>Фото вiдсутнє</p>
                @endif
            </div>

            <div class="col-12">
                <div class="form-group">
                    <strong>Лiцензiя (якщо потрiбно):</strong>
                    <input type="text" name="license" id="license" class="form-control" value="{{old('license') ?? $route->license}}" autocomplete="off">
                </div>
            </div>


            <div id="route-list-edit"></div>

            <br />
            <a href="{{route('sights.list',['routeAdd'=>$route->id])}}"><i class="fa fa-plus"></i> Додати точку</a>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <label for="description">Опис:</label>
                <textarea class="form-control" name="description" id="description" rows="3">{{ old('description') ?? $route->description}}</textarea>
            </div>
        </div>
        
    </div>

    <br />
    <div class="row">
        <p><button type="submit" class="btn btn-primary">Зберегти</button>
        @if(!$route->finished)
            <a href="{{route('routes.publish',$route->id)}}" class="btn btn-warning"><i class="fas fa-flag-checkered"></i> Опублiкувати маршрут</a>
        @endif
        </p>
    </div>
</form>



@endsection

@section('javascript')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    function fillEditorContent(e) {
        this.setContent(`{!! str_replace('`','\'',$route->description ?? old('description') ?? '' ) !!}`);
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
</script>
@endsection