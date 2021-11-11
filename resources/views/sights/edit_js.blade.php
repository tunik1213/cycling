@php
    $districts = App\Models\District::orderBy('name')->get();
@endphp
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    $(function() {
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

        $('#latitude').on('paste', function(e){
            e.preventDefault();
            cl_text = e.originalEvent.clipboardData.getData('text');
            coords = cl_text.split(',');
            $('#latitude').val(coords[0].trim());
            $('#longitude').val(coords[1].trim());
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
                height : '250'
            });
        
    });
</script>

<style>
.tox-notifications-container,.tox-statusbar {
    display: none !important;
}
</style>