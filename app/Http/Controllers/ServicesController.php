<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
   public function returnServices(){
    if(Auth::check()){
        try {
            $services = services::where('isVisible', true)->get();
            return view('pages.external-services', compact('services'));
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    return dd('You are not authorize');
   }
}
