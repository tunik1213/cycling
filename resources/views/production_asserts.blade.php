@php
    $accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    $gz = (substr_count($accept_encoding, 'gzip')) ? 'gz' : '';
@endphp

@if($type=='css')
    <link href="{{ asset('build/20211015152950.css') }}{{$gz}}" rel="stylesheet">
@elseif($type=='js')
    <script src="{{ asset('build/20211015152950.js') }}{{$gz}}"></script>
@endif