@php // отправим уведомление разработчику
	$dev = App\Models\User::find(1);
	$userId = Auth::user()->id ?? null;
	$text = 'Error 500! Check logs! 
	UserID='.$userId.'
	Current URL='.url()->current();
	$dev->notify(new App\Notifications\CommonNotification($text));
@endphp

@extends('layout')
@section('content')
	<h1>Сталася помилка</h1>

	<a href="/">← На головну</a>
@endsection
