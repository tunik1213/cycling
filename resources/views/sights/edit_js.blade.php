@php
    $districts = App\Models\District::orderBy('name')->get();
@endphp
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    $(function() {

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

        tinymce.init({
                selector: '#description',
                language: 'uk',
                plugins: 'link, emoticons, lists, charmap, paste',
                paste_as_text: true,
                toolbar: 'undo redo | bold italic removeformat | bullist numlist | charmap emoticons link image media',
                menubar: false,
                setup: function(editor) {
                    editor.on('init', fillEditorContent);
                },
                contextmenu: false,
                browser_spellcheck: true,
                relative_urls: false
            });
        
    });
</script>

<style>
.tox-notifications-container,.tox-statusbar {
    display: none !important;
}
</style>