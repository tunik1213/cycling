<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

class VisitController extends Controller
{
    public function getVisitsAWS(Request $request)
    {
        file_put_contents('/var/www/html/cycling/tmp/test.log', var_export($_SERVER,true));
        
    }

    public function postVisitsAWS(Request $request)
    {
	$message = Message::fromRawPostData();

	Visit::retrievеAllVisits();

    }
}
