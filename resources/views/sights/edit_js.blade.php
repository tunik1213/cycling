@php
    $areas = App\Models\Area::orderBy('name')->get();

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

    var areas = [
        @foreach($areas as $a)
            {label: "{{ $a->name }}", id: "{{ $a->id }}"}, 
        @endforeach
        ];
    var districts = []; 

    $('#area').autocomplete({
        source: areas,
        minLength: 0,
        select: function(e, ui) {
            districts = [];    
            $('#area_id').val(ui.item.id);
            $.getJSON('/export/districts/'+ui.item.id, function( json ) {
                $('#district' ).autocomplete('option', 'source', json)
                $('#district_id').val('');
                $('#district').val('').trigger('focus');

            });
        }
    });

    $('#district').autocomplete({
        source: districts,
        minLength: 0,
        select: function(e, ui) {
            $("#district_id").val(ui.item.id);
        }
    });

    var area_id = $("#area_id").val();
    if (area_id) {
        $.getJSON('/export/districts/'+area_id, function( json ) {
            $('#district' ).autocomplete('option', 'source', json)
        });
    }

    $('#district').change(function(e){
        if($(this).val()=='') $('#district_id').val('');
    })

    $('#area, #district').focus(function() {
        $(this).autocomplete('search', $(this).val())
    });

    $('#lat').on('paste', function(e){
        e.preventDefault();
        cl_text = e.originalEvent.clipboardData.getData('text');
        coords = cl_text.split(',');
        $('#lat').val(coords[0].trim());
        $('#lng').val(coords[1].trim());

        checkExisting();
    });

    $('#lat, #lng').change(function(e){
        checkExisting();
    });


    function fillEditorContent(e) {
        this.setContent(`{!! str_replace('`','\'',$sight->description ?? old('description') ?? '' ) !!}`);
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
                    selected=(cat.id=={{$sight->subcategory->id ?? old('subcategory') ?? 'null'}})?'selected':'';
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
        }).then(function(editors) {
            $('#description.changed-input').closest('.form-group').find('#description_ifr').addClass('changed-input');
        });;








        var updateCoordinates = function (lat, lng) {
          document.getElementById('lat').value = lat;
          document.getElementById('lng').value = lng;

          checkExisting();
        }

        var checkExisting = function() {
            lat = $('#lat').val();
            lng = $('#lng').val();
            if(!lat || !lng) return;

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
                draggable: false
              });
          

//          marker.addListener('dragend', function(e) {
//            var position = marker.getPosition();
//            updateCoordinates(position.lat(), position.lng())
//          });

          map.addListener('click', function(e) {
            marker.setPosition(e.latLng);
            updateCoordinates(e.latLng.lat(), e.latLng.lng())
          });

          map.panTo(myLatlng);
        }


    initMap();
</script>

<style>
#map {
   margin: 1rem 0;
   height: 20rem;
 }
</style>