<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\services;
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

    public function newClient(){
        if(auth::check()){
            try {
                $services = services::where('isVisible', true)->get();
                return view('pages.client-form', compact('services'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }

    public function dashboard(){
        if(auth::check()){
            try {
                $clientPaymentStatus = Clients::where('clients.isVisible', true)
                ->leftJoin('company_profiles', 'clients.id', '=', 'company_profiles.company')
                ->select('clients.CompanyName', 'company_profiles.image_path')
                ->get();

            
            return view('pages.dashboard', compact('clientPaymentStatus'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }
}
