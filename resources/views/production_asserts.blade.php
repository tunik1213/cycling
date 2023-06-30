@php
    $accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    $gz = (substr_count($accept_encoding, 'gzip')) ? 'gz' : '';
@endphp

@if($type=='css')
    {{-- <link href="{{ asset('build/20230630153610.css') }}{{$gz}}" rel="stylesheet"> --}}
     <link href="https://velocian.com.ua/build/20230630153610.cssgz" rel="stylesheet"  type="text/css">
@elseif($type=='js')
    <script src="{{ asset('build/20230630153610.js') }}{{$gz}}"></script>
@endif