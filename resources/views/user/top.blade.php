@php
	if(!isset($users))
		$users = App\Models\User::topTravelers();
@endphp

<div id="top-travelers">
	<div class="container info-block">
	<h2 class="info-block-header">Топ мандрiвникiв</h2>
	<div class="info-block-body">
		@include('user.list_partial',['users'=>$users])
	</div>
	<div class="info-block-footer">
		<a class="link-secondary" href="#">Переглянути всi</a>
	</div>
</div>