@php
    $accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    $gz = (substr_count($accept_encoding, 'gzip')) ? 'gz' : '';
@endphp

@if($type=='css')
    <link href="{{ asset('build/20220530153733.css') }}{{$gz}}" rel="stylesheet">
@elseif($type=='js')
    <script src="{{ asset('build/20220530153733.js') }}{{$gz}}"></script>
@endif