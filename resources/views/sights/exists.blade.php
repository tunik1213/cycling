<div class="container alert alert-warning alert-dismissible fade show"  role="alert">
	<div class="row">
		<h2>Найближчi локацiї</h2>
		<p>Переконайся, що нема дублiв!</p>
	</div>
	@foreach($sights as $sight)
		<div class="row">
			<div class="col col-xs-4 col-sm-2">
				<img class="in-container" src="data:image/jpeg;base64,{{base64_encode($sight->image)}}" />
			</div>
			<div class="col col-xs-8 col-sm-10">
				<a href="{{route('sights.show',$sight->id)}}">{{$sight->name}}</a>
			</div>
		</div>
	@endforeach
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
