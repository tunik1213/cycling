@extends('layout')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Пiдкатегорiї</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('subcategories.create') }}"> Додати</a>
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
            <th>Категорiя</th>
            <th></th>
        </tr>
        @foreach ($subcats as $c)
        <tr>
            <td>{{ $c->name }}</td>
            <td>{{ $c->category->name ?? 'не вказано' }}</td>
            <td>
                <form action="{{ route('subcategories.destroy',$c->id) }}" method="POST">
   
                    <a class="btn btn-primary" href="{{ route('subcategories.edit',$c->id) }}"><i class="fas fa-edit"></i></a>
   
                    @csrf
                    @method('DELETE')
      
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
  
    {!! $subcats->links('vendor.pagination.bootstrap-4') !!}
      
@endsection