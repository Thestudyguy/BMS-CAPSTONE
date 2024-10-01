<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    //temporary dd kay ngita pakog 403 template

    public function services()
    {
        if (auth::check()) {
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
        if (auth::check()) {
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

    public function adminHub()
    {
        if (auth::check()) {
            try {
                return view('pages.admin-hub');
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }

    public function newClient()
    {
        if (auth::check()) {
            try {
                $services = services::where('isVisible', true)->get();
                return view('pages.client-form', compact('services'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }

    public function dashboard()
    {
        if (auth::check()) {
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

    public function ChartOfAccounts()
    {
        try {
            if (Auth::check()) {
                $at = AccountType::where('isVisible', true)->get();
                $accounts = Accounts::where('isVisible', true)->get();
                return view('pages.chart-of-account', compact('at', 'accounts'));
            } else {
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function NewAccountType(Request $request)
    {
        try {
            if (Auth::check()) {
                // Validate the request
                $request->validate([
                    'AccountType' => 'required|string|unique:account_types,AccountType',
                    'Category' => 'required|string|in:Asset,Liability,Equity,Expenses',
                ]);

                // Create a new account type
                AccountType::create([
                    'AccountType' => $request['AccountType'],
                    'Category' => $request['Category'],
                    'dataUserEntry' => Auth::user()->id,
                ]);

                return response()->json(['success' => 'Data saved successfully']);
            } else {
                return response()->json(['error' => 'Unauthorized Access'], 403);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('AccountType')) {
                return response()->json(['error' => 'Account Type already exists'], 409);
            }
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred: ' . $th->getMessage()], 500);
        }
    }


    public function NewAccount(Request $request) {
        Log::info($request->all());
    }
}

// try {
//     if(Auth::check()){

//     }else{

//     }
// } catch (\Throwable $th) {
//     throw $th;
// }
