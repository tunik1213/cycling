@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Список районiв</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('districts.create') }}">Додати</a>
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
            <th>Область</th>
            <th>Назва</th>
            <th>Герб</th>
        </tr>
        @foreach ($districts as $d)
        <tr>
            <td><a href="{{ route('areas.show',$d->area->id) }}">{{ $d->area->name }}</a></td>
            <td><a href="{{ route('districts.show',$d->id) }}">{{ $d->name }}</a></td>
            <td><img src="{{ route('districts.image',$d->id) }}"/></td>
            <td>
                <a class="btn btn-primary" href="{{ route('districts.edit',$d->id) }}"><i class="fas fa-edit"></i></a>
            </td>
            <td>
                <form action="{{ route('districts.destroy',$d->id) }}" method="POST">
   
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

@endsection