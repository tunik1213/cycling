@extends('layout')

@section('content')
<div class="container">
	<form action="{{route('feedback.create')}}" method="POST">
		@csrf

		<div class="form-group">
			<label for="text">Залишити вiдгук:</label>
			<textarea name="text" class="form-control" rows="5" autofocus></textarea>
		</div>
		<br />

		<div class="form-group">
			<label for="contacts">Ваш контакт для вiдповiдi (необов'язково):</label>
			<input type="text" name="contacts" class="form-control"/>
		</div>
		<br />

		<button type="submit" class="btn btn-primary">Вiдправити</button>
	</form>
</div>
@endsection