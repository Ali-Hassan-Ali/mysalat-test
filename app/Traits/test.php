<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

trait TraitFirebase {

	
   	public function Send($title, $token)
    {
    	$data = '{
			"to" :"'.$token.'",
			"notification" : {
				"body" : "'.$title.'",  "title":"'.$title.'"
			},
			"data": {
				"click_action": "FLUTTER_NOTIFICATION_CLICK"
			}
		}';

	    $response = Http::withHeaders([
	        'Authorization'  => env("AUTHORIZATION_KEY"),
	        'Content-Type'   => 'application/json',
	    ])->withBody($data, 'application/json')
	      ->post('https://fcm.googleapis.com/fcm/send');


    }//end of fun

}//end of Trait