<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sight;
use App\Models\Activity;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use App\Models\Image;

class CrontabController extends Controller
{
    public $gm;

    public function __construct()
    {
        $this->gm = \GoogleMaps::load('directions');
    }

    public function weekly(): void
    {
        self::removeUnusedImages();
    }

    private static function removeUnusedImages(): void
    {
        $images = Image::all();

        foreach ($images as $i) {
            if($i->UsageCount() == 0) {
                $i->delete();
            }
        }
    }

}
