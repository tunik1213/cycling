@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: {{$sight->name}}</title>
    <meta property="og:image" content="{{route('sights.image',$sight->id)}}" />

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vuex"></script>
@endsection

@section('content')

@include('sights.show_partial',['h1'=>true])

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9397290056752587"
     crossorigin="anonymous"></script>
<!-- adaptive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9397290056752587"
     data-ad-slot="2502468467"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>


<br />
<div id="comments-container" object-id="{{$sight->id}}" object-type="sight">
    @include('comments.list',['comments'=>$sight->comments0()])
</div>

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
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
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
