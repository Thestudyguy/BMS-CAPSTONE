<?php

namespace App\Http\Controllers;

use App\Models\AccountDescription;
use App\Models\Accounts;
use App\Models\AccountType;
use App\Models\Clients;
use App\Models\services;
use App\Models\ServicesSubTable;
use App\Models\SystemProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use \App\Models\User;
use Illuminate\Support\Facades\Validator;

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
                $client = Clients::where('isVisible', true)->get();
                $clientCount = count($client);
                return view('pages.dashboard', compact('clientPaymentStatus', 'clientCount'));
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
                // $accounts = Accounts::where('isVisible', true)->get();
                $account = Accounts::where('accounts.isVisible', true)->
                select('accounts.AccountName', 'accounts.id', 'account_types.AccountType', 'account_types.Category')
                ->join('account_types', 'account_types.id', '=', 'accounts.AccountType')->get();
                return view('pages.chart-of-account', compact('at', 'account'));
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


    public function NewAccount(Request $request)
    {
        try {
            if (Auth::check()) {
                $request->validate([
                    'AccountName' => 'required|string|unique:accounts,AccountName',
                    // 'Category' => 'required|string|in:Asset,Liability,Equity,Expenses',
                ]);
                
                Accounts::create([
                    'AccountName' => $request['AccountName'],
                    'AccountType' => $request['AccountType'],
                    'dataUserEntry' => Auth::user()->id,
                ]);

                return response()->json(['success' => 'Data saved successfully']);
            } else {
                return response()->json(['error' => 'Unauthorized Access'], 403);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('AccountName')) {
                return response()->json(['error' => 'Account already exists'], 409);
            }
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred: ' . $th->getMessage()], 500);
        }
    }
    public function Users(){
    try {
        if(Auth::check()){
            $users = User::where('isVisible', true)->get();
            return view('pages.users', compact('users'));
        }else{
            dd('unauthorized access');
        }
    } catch (\Throwable $th) {
        throw $th;
    }

    }

    public function NewUser(Request $request){
            try {
        if(Auth::check()){
            Log::info($request);
             $request->validate([
                'FirstName' => 'required|string|max:255',
                'LastName'  => 'required|string|max:255',
                'UserName'  => 'required|string|max:255|unique:users',
                'Email'     => 'required|email|max:25|unique:users',
                'Role'      => 'required|string|max:50',
                'PIN'       => 'required|string|min:4|max:10|unique:users',
                'password'  => 'required|string|confirmed|min:8|unique:users',
            ]);
                User::create([
                    'FirstName' => $request['FirstName'],
                    'LastName' => $request['LastName'],
                    'UserName' => $request['FirstName'],
                    'Email' => $request['FirstName'],
                    'Role' => $request['FirstName'],
                    'PIN' => $request['FirstName'],
                    'password' => $request['FirstName'],
                ]);
                return response()->json(['response'=>'user saved succesfully']);
        }else{
            dd('unauthorized access');
        }
        
    } catch (\Throwable $th) {
        throw $th;
    }
    }

    public function RemoveUser($id){
                try {
            if(Auth::check()){
                User::where('id', $id)->update(['isVisible' => 0]);
                return response()->json(['response' => 'User removed successfully']);
            }else{
                dd('Unauthorized Access');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function UpdateUser(Request $request){
        if (Auth::check()) {
            try {
                $request->validate([
                    'FirstName' => 'required|string|max:255',
                    'LastName'  => 'required|string|max:255',
                    'UserName'  => 'required|string|max:255|unique:users,UserName,' . $request->id,
                    'Email'     => 'required|email|max:25|unique:users,Email,' . $request->id,
                    'Role'      => 'required|string|max:50',
                    'PIN'       => 'required|string|min:4|max:10|unique:users,PIN,' . $request->id,//must save for future reference
                ]);
                
                User::where('id', $request->id)->update([
                    'FirstName' => $request->FirstName,
                    'LastName' => $request->LastName,
                    'UserName' => $request->UserName,
                    'Email' => $request->Email,
                    'Role' => $request->Role,
                    'PIN' => $request->PIN,
                ]);

                return response()->json(['response' => 'User Updated Successfully']);
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }

    public function Settings(){
        try {
        if(Auth::check()){
            $users = User::where('isVisible', true)->get();
            $sysProfile = SystemProfile::first();
            $accounts = Accounts::where('isVisible', true)->get();
            $services = services::where('isVisible', true)->get();
            $ad = AccountDescription::where('isVisible', true)->get();
            return view('pages.settings', compact('users', 'sysProfile', 'accounts', 'services', 'ad'));
        }else{
            dd('Unauthorized Access');
        }
    } catch (\Throwable $th) {
        throw $th;
    }
    }

    public function GetAccountTypes($id){
            try {
            if(Auth::check()){
                $serviceTypes = ServicesSubTable::where('isVisible', true)->where('BelongsToService', $id)->get();
                return response()->json(['account' => $serviceTypes]);
            }else{

            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function NewAccountDescription(Request $request){
            try {
            if(Auth::check()){
                Log::info($request);
                $request->validate([
                    'Description' => 'required|string|max:255|unique:account_descriptions,Description',
                    'TaxType' => 'required|string|max:255',
                    'FormType' => 'required|string|max:255|unique:account_descriptions,FormType',
                    'Price' => 'required|max:255',
                ]);
                $preparedPrice = floatval($request['Price']);
                AccountDescription::create([
                    'Description' => $request['Description'],
                    'TaxType' => $request['TaxType'],
                    'FormType' => $request['FormType'],
                    'Price' => $preparedPrice,
                    'Category' => $request['account'],
                    'account' => $request['Type'],
                    'dataUserEntry' => Auth::user()->id,
                ]);
                return response()->json(['account_description', 'created']);
            }else{
                dd('unauthorized access');
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
