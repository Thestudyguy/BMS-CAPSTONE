<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
    }
    use AuthorizesRequests, ValidatesRequests;
    public function clients(){
        dd('Method called');
        if(Auth::check()){
            Log::info('you are not allowed man');
        }else{
            Log::info('wtf');
        }
    }
}
