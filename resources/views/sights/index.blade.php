@php
    $areas = App\Models\Area::orderBy('name')->get();
@endphp

@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Список пам'яток</h2>
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
            <th>Назва</th>
            <th>Фото</th>
        </tr>
        @foreach ($sights as $s)
        <tr>
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
                Вiдкрити <a class="link-secondary" target="_blank" href="{{$s->gm_link()}}">google maps</a>
            </td>
            <td><img src="data:image/jpeg;base64,{{base64_encode($s->image)}}" /></td>
            <td width="1">
                <a class="btn btn-primary" href="{{ route('sights.edit',$s->id) }}"><i class="fas fa-edit"></i></a>
            </td>
            <td width="1">
                <form action="{{ route('sights.destroy',$s->id) }}" method="POST">
   
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

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
            
        

    });
</script>
@endsection