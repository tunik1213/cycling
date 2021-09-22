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
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Район:</strong>
                <input type="text" name="district" id="district" class="form-control" placeholder="Почнiть набирати назву району" value="{{ $sight->district->name ?? '' }}" autocomplete="off">
            </div>
        </div>

        <input name="district_id" id="district_id" type="hidden" value="{{ $sight->district->id ?? '' }}" />

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control" placeholder="{{$sight->name}}" value="{{ $sight->name }}" autocomplete="off">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group row col-xs-12 col-sm-9 col-md-6">
                <strong>Координати (Ctrl+V):</strong>
                <div class="col">
                    <input type="text" id="latitude" name="lat" value="{{ $sight->lat }}" class="form-control" placeholder="Широта" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" id="longitude" name="lng" value="{{ $sight->lng }}" class="form-control" placeholder="Довгота" autocomplete="off">
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Знiнити фото:</strong>
                    <input type="file" name="sight_image" id="sight_image" class="form-control">
                </div>
                </div>

                <div class="form-group">
                    <label for="description">Опис:</label>
                    <textarea class="form-control" name="description" id="description" rows="3">{{$sight->description}}</textarea>
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
<script type="text/javascript">
    $(function() {

        var districts = [
            @foreach($districts as $d)
                {label: "{{ $d->name }}", id: "{{ $d->id }}"}, 
            @endforeach
            ];

        $('#district').autocomplete({
            source: districts,
            minLength: 0,
            select: function(e, ui) {
                $("#district_id").val(ui.item.id);
            }
        });

        $('#latitude').on('paste', function(e){
            e.preventDefault();
            cl_text = e.originalEvent.clipboardData.getData('text');
            coords = cl_text.split(',');
            $('#latitude').val(coords[0].trim());
            $('#longitude').val(coords[1].trim());
        });
        
    });
</script>
@endsection