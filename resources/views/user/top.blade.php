@php
	$top = new App\Models\Top;
	$top->limit = 4;
    $users = $top->users();
@endphp

<div id="top-travelers">
	<div class="container info-block">
	<h2 class="info-block-header">Топ мандрiвникiв</h2>
	<div class="info-block-body">
		@include('user.list_partial',['users'=>$users])
	</div>
	<div class="info-block-footer">
		<a class="link-secondary" href="{{route('users.top')}}">Переглянути всi</a>
	</div>
</div>