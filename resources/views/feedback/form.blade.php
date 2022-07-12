@extends('layout')

@section('head')
	<title>{{env('APP_NAME')}}: Залишити вiдгук</title>
@endsection

@section('content')
<div class="container">
	<h1>Залишити вiдгук</h1>
	<form action="{{route('feedback.create')}}" method="POST">
		@csrf

		<div class="form-group">
			<label for="text">
				Буль-ласка, напиши, що думаєш про цей проєкт. 
				Якщо помітив помилку, чи може маєш ідею що тут можна покращити - ми будемо дуже раді почути твій відгук! 
				Проєкт ще тільки розвивається, тому зворотній зв'язок від наших користувачів дуже важливий для нас
			</label>
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