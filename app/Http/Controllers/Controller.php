<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(){
        $this->middleware('auth');
    }

    //temporary dd kay ngita pakog 403 template

    public function services(){
        if(auth::check()){
            try {
                return view('pages.external-services');
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }

    public function adminHub(){
        if(auth::check()){
            try {
                return view('pages.admin-hub');
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }
}
