@extends('layout')

@section('content')

    <h1>Назва сайту</h1>
    <h3>Коротка інформація про нашу діяльність</h3>

    <div class="d-flex justify-content-center text-center">
    @guest
        <div id="auth-link-place">
            
                <a class="btn login-strava-btn" href="{{route('strava_login')}}" role="button">
                    Увійти через <span class="font-color-strava">STRAVA</span>
                </a>
            </div>
    
    @else

        

    @endguest
    </div>

@endsection