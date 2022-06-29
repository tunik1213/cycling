@extends('layout')

@section('content')

<div class="container info-block">

	<div class="info-block-header">
		<h1>Велосипеднi маршрути</h1>

		<div class="info-block-body">

			@include('routes.list_partial',['routes'=>$routes])

		</div>

		<div class="info-block-footer">
			{{ $routes->links('vendor.pagination.bootstrap-4') }}
		</div>
	</div>
</div>

@endsection