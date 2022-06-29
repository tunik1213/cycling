@foreach($routes as $route)
	<div class="card-container">

		<div class="card route-card">
		
			<div class="card-title">
				<div class="route-title">
		        	<a href="{{route('routes.show',$route->id)}}">{{ $route->name }}</a>
		        </div>

		        @if(!empty($route->logo_image))
		            <img class="route-logo-image" src="{{route('routes.image',['id'=>$route->id,'type'=>'logo'])}}" alt="Веломаршрут {{$route->name}}">
		        @endif
	    	</div>

		    <div class="card-body">

		    	<div>
		            <strong>Дистанцiя: </strong>{{$route->distance}}км
		        </div>

		        <div>
		        	<strong>{{$route->areas()}}</strong>
		        </div>

		        @if(!empty($route->license))
		            {!! $route->license !!}
		        @endif

		    </div>

	    </div>


	</div>
@endforeach
