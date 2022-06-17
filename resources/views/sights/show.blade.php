@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sight->name}}</title>
@endsection

@section('content')
 
@include('sights.show_partial',['h1'=>true])

<div class="row">
    @include('user.top',['userList'=>$topUsers,'list_title'=>'Топ мандрiвникiв'])
</div>

<div id="sights-nearby"></div>


@endsection


@section('javascript')

   <script type="text/javascript">
        var latlng = [{{$sight->lat}}, {{$sight->lng}}];
        var mapSelector = 'desktop-map';
        if(!$('#'+mapSelector).is(':visible')) {
            mapSelector = 'mobile-map';
        }
        var map = L.map(mapSelector).setView(latlng, 13);
        //L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/1/1/0?access_token={{env('MAPBOX_TOKEN')}}', {
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
        }).addTo(map);

        var marker = L.marker(latlng).addTo(map);



        $.ajax({
            url: '/sights/'+{{$sight->id}}+'/nearby',
            success: function(data){
                $('#sights-nearby').html(data)
            }
        });

   </script>

@endsection