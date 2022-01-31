@php
    $areas = App\Models\Area::orderBy('name')->get();
@endphp

@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Список пам'яток: модерацiя</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('sights.create') }}">Додати</a>
            </div>

            <br />

            <div class="form-group">
                <strong>Вiдбiр по областi:</strong>
                <input type="text" name="area" id="area" class="form-control" placeholder="Уведiть назву областi" value="{{ $area->name ?? '' }}">
            </div>

            <br />
            

        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    {{ $sights->links('vendor.pagination.bootstrap-4') }}

    <table class="table table-bordered">
        <tr>
            <th></th>
            <th>Назва</th>
            <th>Фото</th>
        </tr>
        @foreach ($sights as $s)
        <tr>
            <td><input type="checkbox" sight="{{$s->id}}"></td>
            <td>
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
                <div class="row">
                    {{$s->categoryLink}}
                </div>
                @if($s->district)
                    Район: <a href="{{ route('districts.show',$s->district->id) }}">{{$s->district->name}}</a>
                    <br />
                    Область: <a href="{{ route('areas.show',$s->district->area->id) }}">{{$s->district->area->name}}</a>
                @endif
                <br />
                @if($s->user)
                        Автор:
                        <a href="{{route('userProfile',$s->user->id)}}">{{ $s->user->fullname }}</a>
                        <br />
                @endif
                Вiдкрити <a class="link-secondary" target="_blank" href="{{$s->gm_link()}}">google maps</a>
            </td>
            <td><img src="data:image/jpeg;base64,{{base64_encode($s->image)}}" /></td>
            <td width="1">
                @php
                    $params = ['sight'=>$s->id];
                    if (isset($moderation_uri)) $params['moderation_uri'] = $moderation_uri;
                @endphp
                <div class="container">
                    <div class="row">
                        <a class="btn btn-primary" href="{{ route('sights.edit',$params) }}"><i class="fas fa-edit"></i></a>
                    </div>
                    <form action="{{ route('sights.destroy',$params) }}" method="POST">
                        <br />
                    <div class="row">       
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </table>

    <div id="mass-edit-form" style="border: 1px solid gray; padding: 1rem;">
        <h2>Змiнити вiдмiченi</h2>

        <div class="form-group">
            <strong>Категорiя:</strong>
            <select id="category" name="category" class="form-select" aria-label="Категорiя">
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
            <strong>Пiдкатегорiя:</strong>
            <select disabled id="subcategory" name="subcategory" class="form-select" aria-label="Пiдкатегорiя">
            </select>
        </div>

        <br />

        <button id="mass-save" type="submit">Зберегти</button>

    </div>

    <br />

    {{ $sights->links('vendor.pagination.bootstrap-4') }}

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
                var url = new URL(window.location.href);
                var search_params = url.searchParams;
                search_params.set('area', ui.item.id);
                url.search = search_params.toString();
                window.location.href = url.toString();
            }
        });
            
        

    $('select#category').change(function(e){
        var id = $(this).find(":selected").attr('value');
        $.ajax({
            url: "/export/subcategories",
            data:"id="+id ,
            success: function(data){
                var s = $('select#subcategory');
                s.removeAttr('disabled').find('option').remove();

                s.append('<option value="">Виберіть підкатегорію</option>');
                $.each(data,function(i,cat) {
                    s.append('<option value="'+cat.id+'">'+cat.name+'</option>');
                });
                s.append('<option value="0">Інше (Важко відповісти)</option>');
            }
        });
    });


    $('#mass-save').click(function(e) {
        var sights = [];
        $('input[type=checkbox]:checked').each(function(i, obj) {
            sights.push($(obj).attr('sight'));
        });
        var data = new Object();
        data['sights'] = sights;
        data['category'] = $('select#category').find(":selected").attr('value');
        data['subcategory'] = $('select#subcategory').find(":selected").attr('value');

        $.ajax({
            url: '/sights/massUpdate',
            data: data,
            type: 'post',
            async: false
        });
        location.reload();
    });

    $('#area').focus(function() {
        $(this).autocomplete('search', $(this).val())
    });


    });
</script>
@endsection