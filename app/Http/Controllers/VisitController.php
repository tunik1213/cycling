<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

class VisitController extends Controller
{
    public function postVisitsAWS(Request $request)
    {
    	$message = Message::fromRawPostData();
    	Visit::retrievеAllVisits();
    }
}
