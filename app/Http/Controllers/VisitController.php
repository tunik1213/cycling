<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
    public function getVisitsAWS(Request $request)
    {
        file_put_contents('/var/www/html/cycling/tmp/test.log', var_export($_SERVER,true));
        Visit::retrievеAllVisits();
    }
}
