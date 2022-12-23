@php
    $getParams = '';
    $filters = $sightList->filters();
    if(!empty($filters)) {
        $getParams = '?'.http_build_query($filters);
    }
@endphp

<script type="text/javascript">

    var map = L.map('map');

    $('h1').hide();
    $('#list-loading-text').show();
    map.on('load', function(e){
        $('h1').show();
        $('#list-loading-text').hide();
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18,
    }).addTo(map);

    var geoJsonUrl = '{!!$sightList->geoJsonUrl()!!}';
    var selectedMarker=null;

    $.getJSON(geoJsonUrl, function(data) {
        var geojson = L.geoJson(data, {
            // onEachFeature: function(feature, layer) {
            //     layer.bindPopup('<a target="_blank" href="' + feature.properties.url + '">' + feature.properties.title + '</a>'
            //         +'<img class="marker-preview" src="'+feature.properties.photos[0]+'" />');
            // }
        });
        var markers = L.markerClusterGroup();
        markers.on('click', function (e) {
            if(selectedMarker != null) selectedMarker.setIcon(new L.Icon.Default);

            var selectedMarkerIcon = L.icon({
                iconUrl: '/images/marker-icon-red.png'
            });
            selectedMarker = e.layer;
            selectedMarker.setIcon(selectedMarkerIcon);
            $.ajax('/sights/'+e.layer.feature.properties.id+'/getMapPopupView{{$getParams}}',{
                success: function(result) {
                    $('#map-preview').html(result);
                }
            });
        });
        markers.addLayer(geojson); 
        map.fitBounds(geojson.getBounds());
        //map.addLayer(markers);
        markers.addTo(map);

    });

</script>
