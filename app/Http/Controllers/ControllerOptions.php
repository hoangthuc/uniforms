<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Options;

class ControllerOptions extends Controller
{
    public static function save_faq(Request $request){
      $resulf =  Options::save_faq($request);
      return \GuzzleHttp\json_encode($resulf);
    }
}
