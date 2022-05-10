<script type="text/javascript">

    var map = L.map('map');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
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
            $.ajax('/sights/'+e.layer.feature.properties.id+'/getMapPopupView',{
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