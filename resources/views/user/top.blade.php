@php
	if(!isset($users)) {
		$top = new App\Models\Top;
		$top->limit = 4;
	    $users = $top->users();
	}
	$getParams = [];
	if(isset($sight)) $getParams['sight'] = $sight->id;
@endphp

<div id="top-travelers">
	<div class="container info-block">
	<h2 class="info-block-header">Топ мандрiвникiв</h2>
	<div class="info-block-body">
		@include('user.list_partial',['users'=>$users])
	</div>
	<div class="info-block-footer">
		<a class="link-secondary" href="{{route('users.top',$getParams)}}">Переглянути всi</a>
	</div>
</div>