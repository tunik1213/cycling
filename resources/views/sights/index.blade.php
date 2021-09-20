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
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Назва</th>
            <th>Фото</th>
        </tr>
        @foreach ($sights as $s)
        <tr>
            <td>
                <a href="{{ route('sights.show',$s->id) }}">{{ $s->name }}</a>
                @if($s->district)
                    <br />
                    Район: <a href="{{ route('districts.show',$s->district->id) }}">{{$s->district->name}}</a>
                    <br />
                    Область: <a href="{{ route('areas.show',$s->district->area->id) }}">{{$s->district->area->name}}</a>
                @endif
            </td>
            <td><img src="{{ route('sights.image',$s->id) }}"/></td>
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