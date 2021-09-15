@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Додати новий район</h2>
        </div>
        <div class="pull-right">
            <a href="{{ route('districts.index') }}">← Назад</a>
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

<form action="{{ route('districts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Область:</strong>
                <input type="text" name="area" id="area" class="form-control" placeholder="Почнiть набирати назву областi" value="{{ old('area') }}">
            </div>
        </div>

        <input name="area_id" id="area_id" type="hidden" value="{{ old('area_id') }}" />

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Назва:</strong>
                <input type="text" name="name" class="form-control" placeholder="Запорiзький" value="{{ old('name') }}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Зображення гербу:</strong>
                <input type="file" name="district_image" id="district_image" class="form-control">
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Зберегти</button>
        </div>
    </div>
</form>
@endsection

@section('javascript')
<script type="text/javascript">
    $(function() {

        var areas = [
            @foreach($areas as $a)
                {label: "{{ $a->name }}", id: "{{ $a->id }}"}, 
            @endforeach
            ];

        $('#area').autocomplete({
            source: areas,
            minLength: 0,
            select: function(e, ui) {
                $("#area_id").val(ui.item.id);
            }
        });
            
        

    });
</script>
@endsection