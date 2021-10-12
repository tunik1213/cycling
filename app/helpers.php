<?php

function prepare_external_links(?string $html) : string
{
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

    $dom = new DOMDocument;
    @$dom->loadHTML($html,LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $links = $dom->getElementsByTagName('a');

    foreach ($links as $link){
        $url = $link->getAttribute('href');
        if (url_is_internal($url)) continue;

        $link->setAttribute('target','_blank');
        $link->setAttribute('rel','nofollow');
    }

    $result = $dom->saveHTML();
    return mb_convert_encoding($result,'UTF-8','HTML-ENTITIES');
}
function url_is_internal(string $url) : bool
{
    $url = trim($url);
    if($url[0]==='/') return true;
    if(strpos($url,env('APP_URL'))===0) return true;

    return false;
}