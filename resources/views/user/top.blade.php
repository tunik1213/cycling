@php
	$users = $userList->index();
@endphp

<div id="top-travelers">
	@if($users->count()>0)
		<div class="container info-block">
		<h2 class="info-block-header">Топ мандрiвникiв</h2>
		<div class="info-block-body">
			@include('user.list_partial',['users'=>$users])
		</div>
		<div class="info-block-footer">
			<a class="link-secondary" href="{{route('users.list',$userList->filters())}}">Переглянути всi</a>
		</div>
	@else
		{{-- <h2>Стань першим, хто вiдвiдає пам'ятку {{$sight->name}}</h2> --}}
	@endif
</div>