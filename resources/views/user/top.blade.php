@php
	$users = $userList->index();
@endphp

<div class="top-users">
	@if($users->count()>0)
		<div class="container info-block">
		<h2 class="info-block-header">{{$userList->title()}}</h2>

		@include('user.list_partial',['users'=>$users])

		<div class="info-block-footer">
			<a class="link-secondary" href="{{$userList->listRoute()}}">Переглянути всi</a>
		</div>
	@else
		{{-- <h2>Стань першим, хто вiдвiдає пам'ятку {{$sight->name}}</h2> --}}
	@endif
</div>