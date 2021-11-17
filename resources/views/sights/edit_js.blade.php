@php
    $districts = App\Models\District::orderBy('name')->get();

    $lat = $sight->lat ?? old('lat');
    $lng = $sight->lng ?? old('lng');
    $marker=true;
    $zoom=10;
    if(empty($lat) || empty($lng)) {
        $lat = 50.44134560052034;
        $lng = 30.558969645408013;
        $marker=false;
        $zoom = 7;
    }

@endphp
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_SERVICE_KEY')}}&libraries=places&callback=initMap"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    
    window.onbeforeunload = function(e){return true;}
    $(document).on("submit", "form", function(event){
        window.onbeforeunload = null;
    });

    var districts = [
        @foreach($districts as $d)
            {label: "{{ $d->name }}", id: "{{ $d->id }}"}, 
        @endforeach
        ];

    $('#district').autocomplete({
        source: districts,
        minLength: 0,
        select: function(e, ui) {
            $("#district_id").val(ui.item.id);
        }
    });

    $('#lat').on('paste', function(e){
        e.preventDefault();
        cl_text = e.originalEvent.clipboardData.getData('text');
        coords = cl_text.split(',');
        $('#lat').val(coords[0].trim());
        $('#lng').val(coords[1].trim());
    });


    function fillEditorContent(e) {
        this.setContent(`{!! $sight->description ?? '' !!}`);
    }

    $('select#category').change(function(e){
        var id = $(this).find(":selected").attr('value');
        $.ajax({
            url: "/export/subcategories",
            data:"id="+id ,
            success: function(data){
                var s = $('select#subcategory');
                s.removeAttr('disabled').find('option').remove();

                s.append('<option value="">Виберіть підкатегорію</option>');
                $.each(data,function(i,cat) {
                    selected=(cat.id=={{$sight->subcategory->id ?? 'null'}})?'selected':'';
                    s.append('<option '+selected+' value="'+cat.id+'">'+cat.name+'</option>');
                });
                s.append('<option value="0">Інше (Важко відповісти)</option>');
            }
        });
    });

    if($('select#category').find(":selected").length>0) $('select#category').trigger('change');

    tinymce.init({
            selector: '#description',
            language: 'uk',
            plugins: 'link, emoticons, lists, charmap, paste, textcolor',
            paste_as_text: true,
            toolbar: 'undo redo | bold italic removeformat | forecolor backcolor | bullist numlist | charmap emoticons link image media',
            menubar: false,
            setup: function(editor) {
                editor.on('init', fillEditorContent);
            },
            contextmenu: false,
            browser_spellcheck: true,
            relative_urls: false,
            height : '350'
        });





        var updateCoordinates = function (lat, lng) {
          document.getElementById('lat').value = lat;
          document.getElementById('lng').value = lng;

          $.get( "/sights/find/"+lat+','+lng, { sight: {{$sight->id ?? 'null'}} } )
            .done(function( data ) {
                $('#response-container').html(data);
                $('button[type=submit]').prop('disabled', Boolean(data));
            });
        }

        var initMap = function () {
          var map, marker;
          var myLatlng = {
            lat: {{$lat}},
            lng: {{$lng}}
          };

          map = new google.maps.Map(document.getElementById('map'), {
            zoom: {{$zoom}},
            center: myLatlng
          });

          
              marker = new google.maps.Marker({
                @if($marker)
                position: myLatlng,
                @endif
                map: map,
                draggable: true
              });
          

          marker.addListener('dragend', function(e) {
            var position = marker.getPosition();
            updateCoordinates(position.lat(), position.lng())
          });

          map.addListener('click', function(e) {
            marker.setPosition(e.latLng);
            updateCoordinates(e.latLng.lat(), e.latLng.lng())
          });

          map.panTo(myLatlng);
        }


    initMap();
</script>

<style>
.tox-notifications-container,.tox-statusbar {
    display: none !important;
}
#map {
   margin: 1rem 0;
   height: 20rem;
 }
</style>