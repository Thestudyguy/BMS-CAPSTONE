<?php

namespace App\Http\Controllers;

use App\Models\AccountDescription;
use App\Models\Accounts;
use App\Models\AccountType;
use App\Models\BillingAddedDescriptions;
use App\Models\BillingDescriptions;
use App\Models\ClientJournal;
use App\Models\Clients;
use App\Models\ClientServices;
use App\Models\ServiceRequirement;
use App\Models\services;
use App\Models\ServicesDocuments;
use App\Models\ServicesSubTable;
use App\Models\SubServiceDocuments;
use App\Models\SubServiceRequirement;
use App\Models\SystemProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $totalSales = 0;
            $sales = ClientServices::select(
                'client_services.id as ClientServiceId',
                'client_services.ClientService as Service',
                'services.Price as ServicePrice',
                'services_sub_tables.ServiceRequirementPrice as SubServicePrice',
                'client_services.created_at'
            )
            ->leftJoin('services', 'services.Service', '=', 'client_services.ClientService')
            ->leftJoin('services_sub_tables', 'services_sub_tables.ServiceRequirements', '=', 'client_services.ClientService')
            ->whereYear('client_services.created_at', \Carbon\Carbon::now()->year)
            ->get();

            $monthlySales = array_fill(0, 12, 0);

            foreach ($sales as $sale) {
                // Log::info("Service: $sale->SubServicePrice");
                $servicePrice = $sale->ServicePrice ?? 0;
                $subServicePrice = $sale->SubServicePrice ?? 0;
                
                $month = \Carbon\Carbon::parse($sale->created_at)->month - 1; 

                $monthlySales[$month] += $servicePrice + $subServicePrice;
                $sales = $sale->SubServicePrice += $sale->ServicePrice;
                $totalSales += $sales;
            }
            // Log::info("Start here...");
            // Log::info("End here...");

            $clientPaymentStatus = Clients::where('clients.isVisible', true)
                ->leftJoin('company_profiles', 'clients.id', '=', 'company_profiles.company')
                ->select('clients.CompanyName', 'company_profiles.image_path')
                ->get();

            $client = Clients::where('isVisible', true)->get();
            $clientCount = count($client);

            $incomeBD = DB::table('billing_descriptions')
            ->join('account_descriptions', 'billing_descriptions.description', '=', 'account_descriptions.id')
            ->select(
                'account_descriptions.Category',
                'billing_descriptions.amount'
                )
            ->get();

            $incomeABD = DB::table('billing_added_descriptions')
            ->join('account_descriptions', 'billing_added_descriptions.description', '=', 'account_descriptions.id')
            ->select(
                'account_descriptions.Category',
                'billing_added_descriptions.amount', 'billing_added_descriptions.account'
                )
            ->get();
            $expenses = 0;
            $income = 0;
            $totalDB = 0;
            $totalADB = 0;
            foreach ($incomeABD as $value) {
                if($value->Category === 'Internal'){
                    $totalADB += $value->amount;
                }
            }
            foreach ($incomeBD as $value) {
                if($value->Category === 'Internal'){
                    $totalDB += $value->amount;
                }
            }

            $income = $totalDB + $totalADB;

            $clients = Clients::where('clients.accountCategory', true)
            ->select(
                'journal_expense_months.amount'
            )
            ->join('journal_expenses', 'journal_expenses.client_id', '=', 'clients.id')
            ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
            ->get();

            $expenses = 0;
            foreach ($clients as $value) {
                $expenses += $value->amount;
            }
            return view('pages.dashboard', compact('expenses','income','clientPaymentStatus', 'clientCount', 'monthlySales', 'totalSales'));

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }
    return redirect()->route('login');
}

    



    public function ChartOfAccounts()
    {
        try {
            if (Auth::check()) {
                $at = AccountType::where('isVisible', true)->get();
                // $accounts = Accounts::where('isVisible', true)->get();
                $account = Accounts::where('accounts.isVisible', true)->
                select('accounts.AccountName', 'accounts.id', 'account_types.AccountType', 'account_types.Category', 'account_types.id as ATid')
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
                    'UserName' => $request['UserName'],
                    'Email' => $request['Email'],
                    'Role' => $request['Role'],
                    'PIN' => $request['PIN'],
                    'password' => $request['password'],
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
            $adac = AccountDescription::where('account_descriptions.isVisible', true)
            ->join('account_types', 'account_types.id', '=', 'account_descriptions.account')
            ->join('services_sub_tables', 'services_sub_tables.id', '=', 'account_descriptions.account')
            ->join('services', 'services.id', '=', 'services_sub_tables.BelongsToService')
            ->join('accounts', 'accounts.id', '=', 'account_descriptions.account')
            ->select(
                'account_descriptions.Category',
                'account_descriptions.Description',
                'account_descriptions.TaxType',
                'account_descriptions.FormType',
                'account_descriptions.Price', 'account_descriptions.id',
                'account_descriptions.Category as adCategory',
                'services_sub_tables.ServiceRequirements', 'services_sub_tables.id as sub_service_id',
                'services.Category', 'services.Service', 'services.id as service_id',
                'accounts.AccountName'
            )
            ->get();
            // $adac = AccountDescription::where('account_descriptions.isVisible', true)
            // ->select(
            //     'account_descriptions.Category', 'account_descriptions.Description', 'account_descriptions.TaxType', 'account_descriptions.FormType', 'account_descriptions.Price',
            //     'services_sub_tables.ServiceRequirements', 'services_sub_tables.BelongsToService',
            //     'services.Service' 
            // )
            // ->join('services_sub_tables', 'services_sub_tables.id', '=', 'account_descriptions.account')
            // ->join('services', 'services.id', '=', 'services_sub_tables.BelongsToService')
            // ->get();
            return view('pages.settings', compact('users', 'sysProfile', 'accounts', 'services', 'ad', 'adac'));
        }else{
            dd('Unauthorized Access');
        }
    } catch (\Throwable $th) {
        throw $th;
    }
    }

    public function GetAccountTypes($id){
            try {
                // Log::info($id);
                // return;
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
                
                $request->validate([
                    'Description' => 'required|string|max:255|unique:account_descriptions,Description',
                    'TaxType' => 'required|string|max:255',
                    'FormType' => 'required|string|max:255|unique:account_descriptions,FormType',
                    'Price' => 'required|max:255',
                    'Category' => 'required|max:255',
                ]);
                $preparedPrice = floatval($request['Price']);
                Log::info($request['Type']);
                AccountDescription::create([
                    'Description' => $request['Description'],
                    'TaxType' => $request['TaxType'],
                    'FormType' => $request['FormType'],
                    'Price' => $preparedPrice,
                    'Category' => $request['Category'],
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


    public function ReturnAccounts($id){
        if(Auth::check()){
            try {
                    $preparedAccount = explode('_', $id);
                    Log::info($id);
                    // return;
                    $assetsAT = Accounts::where('isVisible', true)->where('AccountType', $preparedAccount[1])->get();
                    return response()->json(['assets' => $assetsAT]);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }else{
            dd('unauthorized access');
        }
    }
    public function EditCOA(Request $request){
        if(Auth::check()){
            try {
                $existingAccount = Accounts::where('AccountName', $request['AccountName'])
                ->where('id', '!=', $request['id'])
                ->first();

                if ($existingAccount) {
                return response()->json(['message' => 'Account name already exists.'], 400);
                }
                Accounts::where('id', $request['id'])->update([
                    'AccountName' => $request['AccountName'],
                    'AccountType' => $request['AccountType']
                ]);
                return response()->json(['account' => 'Account updated successfully'], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }

    public function AccountantInterface(){
        if(Auth::check()){
            try {
                $journals = ClientJournal::where('client_journals.isVisible', true)
                ->select(
                    'clients.CEO', 'clients.CompanyName', 'clients.id as client_id',
                    'client_journals.journal_id', 'client_journals.JournalStatus', 'client_journals.dataUserEntry',
                    'users.FirstName', 'users.LastName', 'users.Role'
                )
                ->join('clients', 'clients.id', '=', 'client_journals.client_id')
                ->join('users', 'users.id', '=', 'client_journals.dataUserEntry')
                ->get();
                // Log::info($journals);
                return view('pages.journals', compact('journals'));
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }
    public function UpdateJournalStatus(Request $request){
        if(Auth::check()){
            try {
                ClientJournal::where('journal_id', $request['journal_id'])->update(['JournalStatus' => $request['JournalStatus']]);
                return response()->json(['journal-status', 'updated']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }

    public function ArchiveJournalEntry($id){
        if(Auth::check()){
            try {
                ClientJournal::where('journal_id', $id)->update(['isVisible' => false]);
                return response()->json(['Journal Entry', 'Move to Archive']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }

    public function EditSystemProfile(Request $request){
        if(Auth::check()){
            try {
                SystemProfile::where('id', 1)->update([
                    'PhoneNumber' => $request['PhoneNumber'],
                    'Email' => $request['Email'],
                    'Address' => $request['Address']
                ]);
                return response()->json(['system-profile' => 'updated']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }
    public function toggleUserLogInPrivilege($id)
    {
        if(Auth::check()){
            try {
                $user = User::findOrFail($id);
                $user->UserPrivilege = $user->UserPrivilege == 1 ? 0 : 1;
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => $user->UserPrivilege == 1 
                        ? 'User login enabled successfully.' 
                        : 'User login disabled successfully.'
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized accecss');
        }
    }

    public function RemoveSubService($id){
        if(Auth::check()){
            try {
                ServicesSubTable::where('id', $id)->update(['isVisible' => false]);
                return response()->json(['sub-service', 'removed']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }
    
    public function UpdateDescription(Request $request){
        if(Auth::check()){
            try {
                $preparedPrice = (float)str_replace(',', '', $request['price']);
                AccountDescription::where('id', $request['ad_id'])->update([
                    'Description' => $request['description'],
                    'TaxType' => $request['taxType'],
                    'FormType' => $request['formType'],
                    'Price' => $preparedPrice,
                    'Category' => $request['category'],
                    'account' => $request['Type'],
                    'dataUserEntry' => Auth::user()->id,
                ]);
                // Log::info("$preparedPrice type = $type");
                return response()->json(['account_description' => 'updated']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }

    public function RemoveDescription($id){
        if(Auth::check()){
            try {
                AccountDescription::where('id', $id)->update(['isVisible' => false]);
                return response()->json(['description' => 'removed']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }

    public function RemoveCOA($id){
        if(Auth::check()){
            try {
                Accounts::where('id', $id)->update(['isVisible' => false]);
                return response()->json(['description' => 'removed']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }


    public function AddServiceReq(Request $request){
        if(Auth::check()){
            try {
                Log::info($request);
                foreach ($request['form'] as $value) {
                    ServiceRequirement::create([
                        'service_id' => $request['idref'],
                        'req_name' => $value['value']
                    ]);
                }
                return response()->json(['service_name' => 'added'], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{

        }
    }

    public function AddSubServiceReq(Request $request){
        if(Auth::check()){
            try {
                Log::info($request);
                SubServiceRequirement::create([
                    'req_name' => $request['reqName'],
                    'sub_service_id' => $request['idRef']
                ]);
                return response()->json(['service_name' => 'added'], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{

        }
    }

    public function GetServiceReq($id){
        if(Auth::check()){
            try {
                $prepID = explode('_', $id);
                Log::info($prepID[0]);
                if($prepID[1] === 'subservice'){
                    $subServiceReq = SubServiceRequirement::where('sub_service_id', $prepID[2])->get();
                    Log::info($subServiceReq);
                    return response()->json(['serviceReqs' => $subServiceReq, 'category' => 'subservice']);
                }
                if($prepID[1] === 'service'){
                    $serviceReq = ServiceRequirement::where('service_id', $prepID[2])->get();
                    return response()->json(['serviceReqs' => $serviceReq, 'category' => 'service']);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            abort(403, 'unauthorized access');
        }
    }

    public function NewServiceDocument(Request $request)
{
    if (Auth::check()) {
        try {
            $fileIds = $request->input('file_ids', []);
            $category = $request->input('category');
            $files = $request->file('files', []);

            // Log to confirm request data
            Log::info('File IDs: ' . json_encode($fileIds));
            Log::info('Category: ' . $category);

            if (empty($files)) {
                return response()->json(['message' => 'No files uploaded.'], 400);
            }

            foreach ($files as $index => $file) {
                $fileId = $fileIds[$index] ?? null;
                if ($fileId) {
                    $ids = explode('_', $fileId);
                    $serviceId = $ids[1] ?? null;
                    $originalName = $file->getClientOriginalName();
                    $filePath = $file->store('client-files', 'public');

                    $data = [
                        'service_id' => $serviceId,
                        'ReqName' => $originalName,
                        'getClientOriginalName' => $originalName,
                        'getClientMimeType' => $file->getMimeType(),
                        'getSize' => $file->getSize(),
                        'getRealPath' => $filePath,
                        'dataEntryUser' => Auth::user()->id,
                        'isVisible' => true,
                    ];

                    if ($category === 'subservice') {
                        SubServiceDocuments::create($data);
                    } elseif ($category === 'service') {
                        ServicesDocuments::create($data);
                    }
                }
            }

            return response()->json(['message' => 'Files uploaded successfully.']);

        } catch (\Throwable $th) {
            Log::error('Error uploading files: ' . $th->getMessage());
            return response()->json(['message' => 'An error occurred while uploading files.'], 500);
        }
    }

    return response()->json(['message' => 'Unauthorized'], 401);
}

    public function Income(){
        if(Auth::check()){
            try {
                $incomeData = DB::table('clients')
                ->select(
                    'clients.CompanyName', 'clients.CEO',
                    'billings.billing_id', 'billings.created_at',
                    'billing_descriptions.amount',
                    'billing_added_descriptions.amount as addedAmount'
                )
                ->join('billings', 'billings.client_id', '=', 'clients.id')
                ->join('billing_descriptions', 'billing_descriptions.billing_id', '=', 'billings.billing_id')
                ->join('billing_added_descriptions', 'billing_added_descriptions.billing_id', '=', 'billings.billing_id')
                ->get();
                Log::info(json_encode($incomeData, JSON_PRETTY_PRINT));
                return view('pages.income', compact('incomeData'));
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            abort(403, 'unauthorized access');
        }
    }


    public function Expenses() {
        if (Auth::check()) {
            try {
                // Aggregate expenses by journal_id
                $expenses = DB::table('clients')
                    ->where('clients.accountCategory', true)
                    ->select(
                        'client_journals.journal_id', 'client_journals.created_at',
                        // DB::raw('DATE(client_journals.created_at) as created_at'),
                        DB::raw('SUM(journal_expense_months.amount) as total_expense')
                    )
                    ->join('client_journals', 'client_journals.client_id', '=', 'clients.id')
                    ->join('journal_expenses', 'journal_expenses.journal_id', '=', 'client_journals.journal_id')
                    ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
                    ->groupBy('client_journals.journal_id', 'created_at')
                    ->get();
    
                Log::info(json_encode($expenses, JSON_PRETTY_PRINT));
    
                return view('pages.expenses', compact('expenses'));
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            abort(403, 'unauthorized access');
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


