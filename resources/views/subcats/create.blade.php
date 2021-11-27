@extends('layout')
 
@section('content')
<form action="{{ route('subcategories.store') }}" method="POST">
    @csrf

    <strong>Категорiя:</strong>
    <select id="category_id" name="category_id" class="form-select" aria-label="Категорiя">
        @if(empty(old('category_id')))
            <option selected value="0">Виберiть категорiю</option>
        @endif

        @foreach(\App\Models\SightCategory::all() as $cat)
            @php
                $selected = ($cat->id == old('category_id')) ? 'selected' : '';
            @endphp
            <option {{$selected}} value="{{$cat->id}}">
               {{$cat->name}}
            </option>

        @endforeach
    </select>


    <div class="form-group">
        <strong>Назва:</strong>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" autocomplete="off">
    </div>
      
      <br />
    <div class="row">
        <p><button type="submit" class="btn btn-primary">Зберегти</button></p>
    </div>
      </form>
@endsection