<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sight;
use App\Models\Activity;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class CrontabController extends Controller
{
    public $gm;

    public function __construct() 
    {
        $this->gm = \GoogleMaps::load('directions');
    }

    public function parseActivityNames() : void
    {
	$acts = Activity::whereNull('name')->get();

         foreach($acts as $a) {
            $url = 'https://www.strava.com/activities/'.$a->strava_id;
            try {
                $html = file_get_contents($url);
            } catch (\Throwable $e) {
                echo    $e->getMessage();
                exit;
            }
            // $matches = [];
            // preg_match('/<meta content=\'(.*?)\' property=\'twitter:title\'>/mi', $html, $matches);

            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($html);
            libxml_use_internal_errors(false);


            $metas = $doc->getElementsByTagName('meta');

            foreach($metas as $m) {
                $ats = $m->attributes;
                if(($ats->getNamedItem('property')->value ?? '') == 'twitter:title') {
                    $h1 = $ats->getNamedItem('content')->value;
                    break;
                }
                
            }


            $a->name = $h1;
            $a->save();

         }
    }
   
}

