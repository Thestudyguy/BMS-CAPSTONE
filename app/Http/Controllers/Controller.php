<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\Clients;
use App\Models\services;
use App\Models\ServicesSubTable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
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
    public function addClientServices(Request $request)
    {
        if(auth::check()){
            Log::info($request['id']);
            try {
                $services = Services::where('isVisible', true)->get();
                $client = Clients::where('id', $request['id'])->select('CompanyName', 'id')->first();
                foreach ($services as $service) {
                    $subServices = ServicesSubTable::where('isVisible', true)->where('id', $service->id)->get();
                }
    
                return view('forms.services-form', compact('services', 'client'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('You are not allowed');
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

    public function ChartOfAccounts(){
        try {
            if(Auth::check()){
            $at = AccountType::where('isVisible', true)->get();
            return view('pages.chart-of-account', compact('at'));
            }else{
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function NewAccountType(Request $request){
        try {
            if(Auth::check()){
                Log::info($request['AccountType']);
                Log::info($request['Category']);
                AccountType::create([
                    'AccountType' => $request['AccountType'],
                    'Category' => $request['Category'],
                    'dataUserEntry' => Auth::user()->id
                ]);
                return response()->json(['success' => 'data saved successfully']);
            }else{
                dd('Unauthorized Access');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

// try {
//     if(Auth::check()){

//     }else{

//     }
// } catch (\Throwable $th) {
//     throw $th;
// }
