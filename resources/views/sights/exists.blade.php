<div class="container alert alert-warning alert-dismissible fade show"  role="alert">
	<div class="row">
		<div class="col col-xs-4 col-sm-2">
			<img class="in-container" src="data:image/jpeg;base64,{{base64_encode($sight->image)}}" />
		</div>
		<div class="col col-xs-8 col-sm-10">
			<p>Пам'ятка з такими координамати iснує</p>
			<a href="{{route('sights.show',$sight->id)}}">{{$sight->name}}</a>
		</div>
	</div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
