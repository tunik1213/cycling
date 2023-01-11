@php
    $accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    $gz = (substr_count($accept_encoding, 'gzip')) ? 'gz' : '';
@endphp

@if($type=='css')
    <link href="{{ asset('build/20230111201434.css') }}{{$gz}}" rel="stylesheet">
@elseif($type=='js')
    <script src="{{ asset('build/20230111201434.js') }}{{$gz}}"></script>
@endif