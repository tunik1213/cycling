@extends('layout')
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Список областей</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('areas.create') }}">Додати</a>
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
            <th>Герб</th>
        </tr>
        @foreach ($areas as $a)
        <tr>
            <td><a href="{{ route('areas.show',$a->id) }}">{{ $a->name }}</a></td>
            <td><img src="{{ route('areas.image',$a->id) }}"/></td>
            <td width="1">
                <a class="btn btn-primary" href="{{ route('areas.edit',$a->id) }}"><i class="fas fa-edit"></i></a>
            </td>
            <td width="1">
                <form action="{{ route('areas.destroy',$a->id) }}" method="POST">
   
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

@endsection