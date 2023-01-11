<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['visitsVerified']);
    }

    public function postVisitsAWS(Request $request)
    {
    	$message = Message::fromRawPostData();
    	Visit::retrievеAllVisits();
    }

    public function visitsVerified(Request $request)
    {
        $user = Auth::user();
        return var_export(!empty($user->visits_verified_at),true);
    }
}
