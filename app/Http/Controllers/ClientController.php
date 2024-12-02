<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Mail\MailClientNewServices;
use App\Mail\MailClientServices;
use App\Models\AccountDescription;
use App\Models\Accounts;
use App\Models\AccountType;
use App\Models\ActivityLog;
use App\Models\BillingAddedDescriptions;
use App\Models\Billings;
use App\Models\ClientBilling;
use App\Models\ClientBillingService;
use App\Models\ClientBillingSubService;
use App\Models\ClientJournal;
use App\Models\ClientRepresentative;
use App\Models\Clients;
use App\Models\ClientServices;
use App\Models\CompanyProfile;
use App\Models\journal_adjustments;
use App\Models\journal_assets;
use App\Models\journal_expense;
use App\Models\journal_expense_month;
use App\Models\journal_income;
use App\Models\journal_income_months;
use App\Models\journal_liabilities;
use App\Models\journal_owners_equity;
use App\Models\services;
use App\Models\ServicesSubTable;
use App\Models\SubServiceDocuments;
use App\Models\SubServiceRequirement;
use App\Models\SystemProfile;
use Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Mail;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;

class ClientController extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf();
    }
    public function returnClientData()
    {
        if (Auth::check()) {
            try {
                //firm = true
                //client = false
                $services = services::where('isVisible', true)->get();
                $clients = Clients::where('isVisible', true)->get();
                return view('pages.clients', compact('services', 'clients'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }
    public function CreateNewClient(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authorized'], 403);
        }
        $validatedData = $request->validate([
            'CompanyName' => 'required|string|max:255|unique:clients',
            'CompanyAddress' => 'required|string|max:255',
            // 'TIN' => 'required|string|max:50',
            'CompanyEmail' => 'required|email|max:255|unique:clients',
            'CEO' => 'required|string|max:255',
            'CEODateOfBirth' => 'required|date',
            'CEOContactInformation' => 'required|string|max:255|unique:clients',
            'RepresentativeName' => 'required|string|max:255',
            'RepresentativeContactInformation' => 'required|string|max:255',
            'RepresentativeDateOfBirth' => 'required|date',
            'RepresentativePosition' => 'required|string|max:255',
            'RepresentativeAddress' => 'required|string|max:255',
            'profile' => 'nullable|file|image|max:2048',
        ]);
        try {
            DB::beginTransaction();
            $client = new Clients();
            $client->CompanyName = $validatedData['CompanyName'];
            $client->CompanyAddress = $validatedData['CompanyAddress'];
            $client->TIN = '123123';
            $client->CompanyEmail = $validatedData['CompanyEmail'];
            $client->CEO = $validatedData['CEO'];
            $client->CEODateOfBirth = $validatedData['CEODateOfBirth'];
            $client->CEOContactInformation = $validatedData['CEOContactInformation'];
            $client->AccountCategory = $request['AccountCategory'] === 'Firm';
            $client->dataEntryUser = Auth::user()->id;
            $client->save();
            $representative = new ClientRepresentative();
            $representative->CompanyRepresented = $client->id;
            $representative->RepresentativeName = $validatedData['RepresentativeName'];
            $representative->RepresentativeContactInformation = $validatedData['RepresentativeContactInformation'];
            $representative->RepresentativeDateOfBirth = $validatedData['RepresentativeDateOfBirth'];
            $representative->RepresentativePosition = $validatedData['RepresentativePosition'];
            $representative->RepresentativeAddress = $validatedData['RepresentativeAddress'];
            $representative->dataEntryUser = Auth::user()->id;
            $representative->save();
            if ($request->hasFile('profile')) {
                $profile = $request->file('profile');
                $fileName = 'client_' . $client->id . '_' . time() . '.' . $profile->getClientOriginalExtension();
                $profilePath = $profile->storeAs('client-files', $fileName, 'public');
                $companyProfile = new CompanyProfile();
                $companyProfile->company = $client->id;
                $companyProfile->image_path = $profilePath;
                $companyProfile->dataUserEntry = Auth::user()->id;
                $companyProfile->save();
            }
            $userAgent = $request->header('User-Agent');
            $browserDetails = CustomHelper::getBrowserDetails($userAgent);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'New Client Created',
                'activity' => 'Created a new client',
                'description' => "New Client $validatedData[CompanyName]- $validatedData[CEO]",
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'browser' => $browserDetails['browser'] ?? null,
                'platform' => $browserDetails['platform'] ?? null,
                'platform_version' => $browserDetails['platform_version'] ?? null,
            ]);
            DB::commit();
            return response()->json(['success' => 'Client, representative, and profile saved successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating new client: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while saving data'], 500);
        }
    }


    public function ClientServices(Request $request)
{
    if (Auth::check()) {
        try {
            DB::beginTransaction();

            // Validate incoming request data
            $request->validate([
                'client_id' => 'required|integer|exists:clients,id',
                'services' => 'required|array',
                'services.*.serviceName' => 'required|string',
                'services.*.servicePrice' => 'required|numeric',
                // Add validation for any other fields in services if necessary
            ]);

            $clientId = $request->input('client_id');
            
            // Loop through each service in the request
            foreach ($request['services'] as $services) {
                // Handle serviceName and split it correctly
                $serviceName = explode('_', $services['serviceName']);
                
                if (count($serviceName) < 4) {
                    // Handle case where serviceName is not split correctly
                    return response()->json(['error' => 'Invalid service name format.'], 400);
                }

                Log::info("Processing service: " . $services['serviceName']); // Log the service name
                
                // Check if the service already exists for the client
                $isServiceExisting = ClientServices::where('isVisible', true)
                    ->where('Client', $clientId)
                    ->where('ClientService', $serviceName[0])
                    ->first();

                if ($isServiceExisting) {
                    // Prepare a string from the service name array
                    $serviceNameString = implode('', $serviceName);
                    return response()->json(['Conflict' => "Service $serviceName[0] already exists for this client."], 409);
                }

                // Optionally handle file uploads here if needed
                // Example:
                // $file = $services['serviceFile'] ?? null;
                // if ($file instanceof \Illuminate\Http\UploadedFile) {
                //     $filePath = $file->storeAs('client-files', $file->getClientOriginalName(), 'public');
                // }

                // Create the service entry for the client
                ClientServices::create([
                    'Client' => $clientId,
                    'ClientService' => $serviceName[0], // Assuming serviceName[0] is the key you need
                    'ClientServiceProgress' => 'Pending',
                    'dataEntryUser' => Auth::user()->id,
                    'serviceCategory' => "{$serviceName[2]}_{$serviceName[3]}", // Example service category
                    'isVisible' => true,
                ]);

                // Send an email to the client about the new service
                $this->MailNewServiceToClient($request['client_id'], $request['services']);
            }

            // Log the activity for adding new services to the client
            $userAgent = $request->header('User-Agent');
            $browserDetails = CustomHelper::getBrowserDetails($userAgent);
            $client = Clients::where('id', $clientId)->first();
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'New Services Created',
                'activity' => 'Added a new Service to client',
                'description' => "Added services to client {$client->CEO} - {$client->CompanyName}",
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'browser' => $browserDetails['browser'] ?? null,
                'platform' => $browserDetails['platform'] ?? null,
                'platform_version' => $browserDetails['platform_version'] ?? null,
            ]);

            DB::commit();
            return response()->json(['message' => 'Services added successfully.'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error occurred: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while processing the services.'], 500);
        }
    } else {
        return response()->json(['error' => 'Unauthorized Access'], 401);
    }
}

    public function MailNewServiceToClient($client, $services){
        if(Auth::check()){
            try {
                $clientEmail = Clients::where('id', $client)->first();
                Mail::to($clientEmail->CompanyEmail)->send(new MailClientNewServices($clientEmail, $services));
                return response()->json(['mail-client-services-status' => 'Mail Sent'], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('Unauthorized Accecss');
        }
    }
    
    public function viewClientProfile(Request $request)
    {
        if (Auth::check()) {
            Log::info($request->id);
            $client = Clients::where('id', $request->id)->first();
            if (!$client) {
                return redirect()->back()->with('error', 'Client not found.');
            }
            $dataEntryUserId = $client->dataEntryUser;
            $clientServices = ClientServices::where('isVisible', true)->where('Client', $request->id)->get();
            $clientProfile = CompanyProfile::where('isVisible', true)
                ->where('company', $request->id)
                ->select('image_path')
                ->first();
            $repInfo = ClientRepresentative::where('CompanyRepresented', $request->id)->get();
            $user = User::where('id', $dataEntryUserId)->select('FirstName', 'LastName', 'role', 'id')->first();
            return view('pages.client-profile', compact('client', 'clientServices', 'clientProfile', 'repInfo', 'user'));
        } else {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
    }

    public function ViewServiceDocuments($id){
        if(Auth::check()){
            try {
                Log::info($id);
                $prepID = explode('_', $id);

                if($prepID[1] === 'subservice'){
                    $subServiceDocs = SubServiceRequirement::where('sub_service_requirements.sub_service_id', $prepID[2])
                    ->select(
                        'sub_service_requirements.req_name',
                        'sub_service_documents.getClientOriginalName',
                        'sub_service_documents.ReqName',
                        'sub_service_documents.getSize',
                        'sub_service_documents.getRealPath',
                        'sub_service_documents.getClientMimeType'
                        )
                        ->leftJoin('sub_service_documents', 'sub_service_documents.service_id', '=', 'sub_service_requirements.id')
                    ->get();
                    Log::info($subServiceDocs);
                    return response()->json(['docsData' => $subServiceDocs]);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            abort(403, 'unauthorized access');
        }
    }

    public function RemoveClientService($id){
        if(Auth::check()){
            try {
                ClientServices::where('id', $id)->update(['isVisible' => false]);
                return response()->json(['service' => 'removed']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            abort(403, 'unauthorized access');
        }
    }

    public function ClientJournal(Request $request)
    {
        if (Auth::check()) {
            $client = Clients::where('id', $request->id)->first();
            $users = User::where('isVisible', true)->get();
            $journals = ClientJournal::where('isVisible', true)->where('client_id', $request->id)->get();
            return view('pages.client-journal', compact('client', 'journals', 'users'));
        } else {
            dd('unauthorize access');
        }
    }


    public function ClientBilling(Request $request)
    {
            if (Auth::check()) {
            try {
                DB::beginTransaction();
                    // $uniqueId = Str::random(6);
                    $clientId = $request->id;
                    function generateAlphanumericId($length = 6)
                {
                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $uniqueId = '';
                    for ($i = 0; $i < $length; $i++) {
                        $uniqueId .= $characters[rand(0, strlen($characters) - 1)];
                    }
                    return $uniqueId;
                }
    
                $uniqueId = generateAlphanumericId();
                $ads = AccountDescription::where('account_descriptions.isVisible', true)
                ->join('account_types', 'account_types.id', '=', 'account_descriptions.account')
                ->join('services_sub_tables', 'services_sub_tables.id', '=', 'account_descriptions.account')
                ->join('services', 'services.id', '=', 'services_sub_tables.BelongsToService')
                ->select(
                    'account_descriptions.Category',
                    'account_descriptions.Description',
                    'account_descriptions.TaxType',
                    'account_descriptions.FormType',
                    'account_descriptions.Price',
                    'account_descriptions.id',
                    'account_descriptions.Category as adCategory',
                    'services_sub_tables.ServiceRequirements',
                    'services.Category', 'services.Service', 'services.Price', 'services.id as ParentServiceID'
                )
                ->get();
                $currentDate = date('Y-m-d');
                $client = Clients::where('id', $clientId)->first();
                $systemProfile = SystemProfile::all();
    
                $clientServicesData = DB::table('client_services as cs_parent')
                    ->join('services as s', 'cs_parent.ClientService', '=', 's.Service')
                    ->where('cs_parent.Client', $clientId)
                    ->where('cs_parent.isVisible', true)
                    ->select(
                        'cs_parent.ClientService as ParentService',
                        's.id as ServiceID', 's.Price as ParentServicePrice',
                        'cs_parent.id as ParentServiceID'
                    )
                    ->get();
    
                $result = [];
    
                foreach ($clientServicesData as $service) {
                    // $result[$clientId]['Service'][$service->ParentService] = [
                    //     'sub_service' => [],
                    // ];
                    $result[$clientId]['Service'][$service->ParentService]['parent_service_id'][$service->ServiceID] = [
                        // 'sub_service' => [],
                        'parentServicePrice' => $service->ParentServicePrice
                    ];
                    $subServices = DB::table('client_services as cs_sub')
                        ->join('services_sub_tables as ss', 'cs_sub.ClientService', '=', 'ss.ServiceRequirements')
                        ->where('cs_sub.Client', $clientId)
                        ->where('ss.BelongsToService', $service->ServiceID)
                        ->where('cs_sub.isVisible', true)
                        ->select(
                            'ss.ServiceRequirements', 'ss.ServiceRequirementPrice',
                            'ss.id as SubServiceID',
                            'cs_sub.ClientService as SubServiceName'
                        )
                        ->get();
    
                    foreach ($subServices as $subService) {
                        $result[$clientId]['Service'][$service->ParentService]['sub_service'][$subService->ServiceRequirements]['sub_service_id'][$subService->SubServiceID] = [
                            'sub_service_price' => $subService->ServiceRequirementPrice,
                            // 'account_descriptions' => [],
                        ];
    
                        $accountDescriptions = AccountDescription::where('account', $subService->SubServiceID)
                            ->where('isVisible', true)
                            ->get();
    
                        foreach ($accountDescriptions as $accountDescription) {
                            $result[$clientId]['Service'][$service->ParentService]['sub_service'][$subService->ServiceRequirements]['sub_service_id'][$subService->SubServiceID]['account_descriptions'][] = [
                                'Category' => $accountDescription->Category,
                                'Description' => $accountDescription->Description,
                                'TaxType' => $accountDescription->TaxType,
                                'FormType' => $accountDescription->FormType,
                                'Price' => $accountDescription->Price,
                                'adID' => $accountDescription->id,
                            ];
                        }
                    }
                }
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Billing Created',
                    'activity' => 'Created a new billing',
                    'description' => "New Billing for $client->CEO - $client->CompanyName",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                Log::info('Client IP: ' . $userAgent);
                DB::commit();
                // Log::info(json_encode($result, JSON_PRETTY_PRINT));
                response()->json(['services' => $result, 'current_date' => $currentDate, 'ads' => $ads]);
                return view('pages.billings', compact('clientId', 'result', 'systemProfile', 'client', 'currentDate', 'ads', 'uniqueId'));
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }

    public function ClientBillingLists(Request $request)
    {
        if (Auth::check()) {
            $client = Clients::where('id', $request['id'])->first();
            $uniqueId = Str::uuid()->toString();
            $billings = ClientBilling::where('client_id', $request['id'])->get();
            $clientBilling = Billings::where('client_id', $request['id'])->get();
            return view('pages.client-billing-lists', compact('client', 'uniqueId', 'billings', 'clientBilling'));
        } else {
            dd('unauthorized access');
        }
    }

    public function ClientJournalForm(Request $request)
    {
        if (Auth::check()) {
            Log::info($request->id);
            $client = Clients::where('id', $request->id)->first();
            $accounts = Accounts::where('accounts.isVisible', true)
                ->select('accounts.AccountName as Account', 'account_types.AccountType as AT', 'account_types.Category', 'accounts.id')
                ->join('account_types', 'accounts.AccountType', '=', 'account_types.id')
                ->get();
            $ats = AccountType::where('isVisible', true)->where('Category', 'Asset')->get();
            $lts = AccountType::where('isVisible', true)->where('Category', 'Liability')->get();
            $oets = AccountType::where('isVisible', true)->where('Category', 'Equity')->get();
            $ets = AccountType::where('isVisible', true)->where('Category', 'Expenses')->get();
            return view('pages.client-journal-form', compact('client', 'accounts', 'ats', 'lts', 'oets', 'ets'));
        } else {
            dd('unauthorize access');
        }
    }

    private function MailClientServices($clientID)
    {
        try {
            Log::info($clientID);
            $services = ClientServices::where('Client', $clientID)->get();
            $client = Clients::where('id', $clientID)->pluck('CompanyEmail', 'CEO')->first();
            
            FacadesMail::to($client)->send(new MailClientServices($services, testemail: $client));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function NewJournalEntry(Request $request){
        if(Auth::check()){
                function generateAlphanumericId($length = 8)
            {
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $uniqueId = '';
                for ($i = 0; $i < $length; $i++) {
                    $uniqueId .= $characters[rand(0, strlen($characters) - 1)];
                }
                return $uniqueId;
            }
            $uniqueId = generateAlphanumericId();
            try {
                $income = $request['incomeObj'];
                $expense = $request['expensesObj'];
                $assets = $request['assetObj'];
                $liability = $request['liabilityObj'];
                $ownersEquity = $request['oeObj'];
                $adjustments = $request['adjustmentObj'];
                $client = $request['client_id'];
                DB::beginTransaction();
                foreach ($income as $key => $value) {
                    $jie = journal_income::create([
                        'client_id' => $client,
                        'account' => $key,
                        'start_date' => $value['startDate'],
                        'end_date' => $value['endDate'],
                        'journal_id' => $uniqueId  
                    ]);
                    foreach ($value['months'] as $value) {
                        $sanitizedAmount = str_replace(',', '', $value['value']);
                        $preparedAmount = floatval($sanitizedAmount);
                        journal_income_months::create([
                            'income_id' => $jie->id,
                            'month' => $value['incomeMonthName'],
                            'amount' => $preparedAmount
                        ]);

                    }
                }
                foreach ($expense as $key => $value) {
                    $jee = journal_expense::create([
                        'client_id' => $client,
                        'account' => $key,
                        'start_date' => $value['startDate'],
                        'end_date' => $value['endDate'],
                        'journal_id' => $uniqueId
                    ]);

                    foreach ($value['months'] as $value) {
                        $sanitizedAmount = str_replace(',', '', $value['value']);
                        $preparedAmount = floatval($sanitizedAmount);
                        journal_expense_month::create([
                            'expense_id' => $jee->id,
                            'month' => $value['monthName'],
                            'amount' => $preparedAmount
                        ]);
                    }
                }

                foreach ($assets as $key => $value) {
                    foreach ($value['accounts'] as $account) {
                        $preparedAssetAccount = explode('_', $account['assetAccount']);
                        $preparedKey = explode('_', $key);
                        $sanitizedAmount = str_replace(',', '', $account['amount']);
                        $preparedAmount = floatval($sanitizedAmount);
                        journal_assets::create([
                            'client_id' => $client,
                            'asset_category' => $preparedKey[0],
                            'account' => $preparedAssetAccount[0],
                            'journal_id' => $uniqueId,
                            'amount' => $preparedAmount,
                        ]);
                    }
                }
                foreach ($liability as $key => $value) {
                    foreach ($value['accounts'] as $account) {
                        $preparedAssetAccount = explode('_', $account['liabilityAccount']);
                        $preparedKey = explode('_', $key);
                        $sanitizedAmount = str_replace(',', '', $account['amount']);
                        $preparedAmount = floatval($sanitizedAmount);
                        journal_liabilities::create([
                            'client_id' => $client,
                            'account' => $preparedAssetAccount[0],
                            'amount' => $preparedAmount,
                            'journal_id' => $uniqueId,
                        ]);
                    }
                }
                foreach ($ownersEquity as $key => $value) {
                    foreach ($value['accounts'] as $account) {
                        $sanitizedAmount = str_replace(',', '', $account['amount']);
                        $preparedAmount = floatval($sanitizedAmount);
                        $preparedAccount = explode('_', $account['oeAccount']);
                        journal_owners_equity::create([
                            'client_id' => $client,
                            'account' => $preparedAccount[0],
                            'journal_id' => $uniqueId,
                            'amount' => $preparedAmount,
                        ]);
                    }
                }
                $preparedAdjustmentAmountoc = str_replace(',', '', $adjustments['owners_contribution']);
                $preparedAdjustmentAmountow = str_replace(',', '', $adjustments['owners_withdrawal']);
                journal_adjustments::create([
                    'client_id' => $client,
                    'owners_contribution' => $preparedAdjustmentAmountoc,
                    'owners_withdrawal' => $preparedAdjustmentAmountow,
                    'journal_id' => $uniqueId
                ]);
                // Log::info("Client id = $client");
                ClientJournal::create([
                    'client_id' => $client,
                    'journal_id' => $uniqueId,
                    'dataUserEntry' => Auth::user()->id
                ]);
                $clients = Clients::where('id', $client)->first();
                $userAgent = $request->header('User-Agent');
                // $browserDetails = $this->getBrowserDetails($userAgent);
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'New Journal Entry',
                    'activity' => 'New journal entry, journal ID ' . $uniqueId . ' client ' . $clients->CEO.''.', '.''.$clients->CompanyName,
                    'description' => 'New income, expense, asset, liability, owners equity, and adjustments for journal ID ' . $uniqueId . ' for client'. $clients->CEO.''.', '.''.$clients->CompanyName,
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['journal' => 'new journal entry saved successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }
        else{
            dd('unauthorize access');
        }
    }


    public function ViewClientBilling(Request $request)
    {
        if (Auth::check()) {
            try {
                $servicesData = DB::table('billings')
                    ->join('client_billing_services', 'client_billing_services.billing_id', '=', 'billings.billing_id')
                    ->join('services', 'services.id', '=', 'client_billing_services.service')
                    ->where('billings.billing_id', $request['billing_id'])
                    ->select('services.id as service_id', 'services.Service as service_name', 'services.Price as service_price')
                    ->get();

                $servicesHierarchy = [];

                foreach ($servicesData as $service) {
                    $subServicesData = DB::table('client_billing_sub_services')
                        ->join('services_sub_tables', 'services_sub_tables.id', '=', 'client_billing_sub_services.sub_service')
                        ->where('services_sub_tables.BelongsToService', $service->service_id)
                        ->select(
                            'services_sub_tables.ServiceRequirements as sub_service_name',
                            'services_sub_tables.ServiceRequirementPrice as sub_service_price',
                            'services_sub_tables.id as sub_service_id'
                        )
                        ->get();
                    $servicesHierarchy[$service->service_id] = [
                        'service_name' => $service->service_name,
                        'service_price' => $service->service_price,
                        'sub_services' => []
                    ];
                    foreach ($subServicesData as $subService) {
                        $servicesHierarchy[$service->service_id]['sub_services'][$subService->sub_service_id] = [
                            'sub_service_name' => $subService->sub_service_name,
                            'sub_service_price' => $subService->sub_service_price,
                            'account_descriptions' => []
                        ];
                        $accountDescriptions = DB::table('billing_descriptions')
                            ->select(
                                'account_descriptions.Description as account_description',
                                'account_descriptions.Price as account_price'
                            )
                            ->join('account_descriptions', 'billing_descriptions.description', '=', 'account_descriptions.id')
                            ->join('services_sub_tables', 'services_sub_tables.id', '=', 'account_descriptions.account')
                            ->where('billing_descriptions.billing_id', $request['billing_id'])
                            ->where('services_sub_tables.id', $subService->sub_service_id)
                            ->get();
                        $servicesHierarchy[$service->service_id]['sub_services'][$subService->sub_service_id]['account_descriptions'] = $accountDescriptions;
                    }
                }
                $addedDescriptions = BillingAddedDescriptions::where('billing_id', $request['billing_id'])->get();
                return view('pages.view-client-billing', [
                    'systemProfile' => SystemProfile::get(),
                    'client' => Clients::where('id', $request['client_id'])->first(),
                    'billing' => Billings::where('billing_id', $request['billing_id'])->first(),
                    'clientBilling' => $servicesHierarchy, 'addedDescription' => $addedDescriptions
                ]);

            } catch (\Throwable $th) {
                Log::info($th);
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        } else {
            dd('Unauthorized access');
        }
    }

    public function UpdateClientService(Request $request){
        if(Auth::check()){
            try {
                foreach ($request['serviceVal'] as $value) {
                    ClientServices::where('id', $request['serviceId'])->update(['ClientServiceProgress' => $value['value']]);
                }
                return response()->json(['client-service' => 'progress updated'], 200);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized acceess');
        }
    }

    public function UpdateClientCompanyInfo(Request $request){
        if(Auth::check()){
            try {
                DB::beginTransaction();
                Clients::where('id', $request['client_id'])->update([
                    'CompanyName' => $request['CompanyName'],
                    'CompanyAddress' => $request['CompanyAddress'],
                    'CompanyEmail' => $request['CompanyEmail'],
                    'TIN' => $request['TIN'],
                ]);
                $client = Clients::where('id', $request['client_id'])->first();
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Updated Company Info',
                    'activity' => 'Updated Company Info',
                    'description' => "Company Info Updated $client->CEO - $client->CompanyName",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['company-info' => 'updated']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }


    public function updateCompanyProfile(Request $request)
{
    // Log::info($request['client_id']);
    // return;
    if(Auth::check()){
        try {
            DB::beginTransaction();
            $request->validate([
                'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        
            $clientProfile = CompanyProfile::firstOrNew(['company' => $request['client_id']]);
        
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $fileName = 'client_' . $request['client_id'] . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('client-files', $fileName, 'public');
        
                if ($clientProfile->image_path) {
                    Storage::disk('public')->delete($clientProfile->image_path);
                }
        
                $clientProfile->image_path = $filePath;
                $clientProfile->save();
            }
            $client = Clients::where('id', $request['client_id'])->first();
            $userAgent = $request->header('User-Agent');
            $browserDetails = CustomHelper::getBrowserDetails($userAgent);
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Updated Company Profile',
                'activity' => 'Updated Company Profile',
                'description' => "Company Profile Updated $client->CEO - $client->CompanyName",
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'browser' => $browserDetails['browser'] ?? null,
                'platform' => $browserDetails['platform'] ?? null,
                'platform_version' => $browserDetails['platform_version'] ?? null,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }else{
        dd('unauthorized access');
    }
}

public function AuditPage(Request $request){
    // $journalIncomes = journal_income::where('journal_incomes.journal_id', $request->id)
    // ->select(
    //     'journal_incomes.*', 'journal_income_months.*',
    //     'journal_incomes.id as jiID', 'journal_income_months.id as jimID',
    // )
    // ->join('journal_income_months', 'journal_income_months.income_id', '=', 'journal_incomes.id')
    // ->get();
    // $journalExpenses = journal_expense::where('journal_expenses.journal_id', $request->id)
    // ->select(
    //     'journal_expenses.*', 'journal_expense_months.*',
    //     'journal_expenses.id as jeID', 'journal_expense_months.id as jemID'
    // )
    // ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
    // ->get();
    $journalIncome = journal_income::where('journal_incomes.journal_id', $request->id)
    ->where('journal_incomes.isAltered', false)
    ->select(
       'journal_incomes.*', 'journal_income_months.*',
        'journal_incomes.id as jeID', 'journal_income_months.id as jemID'
    )
    ->join('journal_income_months', 'journal_income_months.income_id', '=', 'journal_incomes.id')
    ->get();
    $groupedIncomeData = $journalIncome->groupBy('account')->map(function ($items, $account) {
        return [
            'months' => $items->map(function ($item) {
                return [
                    'incomeMonthName' => $item->month,
                    'value' => number_format($item->amount, 2, '.', '')
                ];
            })->values(),
            'startDate' => $items->first()->start_date,
            'endDate' => $items->first()->end_date
        ];
    })->toArray();
    $journalExpense = journal_expense::where('journal_expenses.journal_id', $request->id)
    ->where('journal_expenses.isAltered', false)
    ->select(
        'journal_expenses.*', 'journal_expense_months.*',
        'journal_expenses.id as jeID', 'journal_expense_months.id as jemID',
        )
        ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
    ->get();
    $groupedExpenseData = $journalExpense->groupBy('account')->map(function ($items, $account) {
        return [
            'months' => $items->map(function ($item) {
                return [
                    'expenseMonthName' => $item->month,
                    'value' => number_format($item->amount, 2, '.', '')
                ];
            })->values(),
            'startDate' => $items->first()->start_date,
            'endDate' => $items->first()->end_date
        ];
    })->toArray();
    Log::info(json_encode($groupedExpenseData, JSON_PRETTY_PRINT));
    $journalAsset = journal_assets::where('journal_id', $request->id)->where('isAltered', false)->get();
    $journalLiability = journal_liabilities::where('journal_liabilities.journal_id', $request->id)
    ->where('journal_liabilities.isAltered', false)
    ->select(
        'journal_liabilities.account', 'journal_liabilities.amount',
        'accounts.accountType', 'account_types.id', 'account_types.AccountType'
        )
    ->join('accounts', 'accounts.AccountName', '=', 'journal_liabilities.account')
    ->join('account_types', 'account_types.id', '=', 'accounts.AccountType')
    ->get();
    $journalOE = journal_owners_equity::where('journal_owners_equities.journal_id', $request->id)
    ->where('journal_owners_equities.isAltered', false)
    ->select(
        'journal_owners_equities.account', 'journal_owners_equities.amount',
        'accounts.accountType', 'account_types.id', 'account_types.AccountType'
        )
    ->join('accounts', 'accounts.AccountName', '=', 'journal_owners_equities.account')
    ->join('account_types', 'account_types.id', '=', 'accounts.AccountType')
    ->get();
    $journaladjustment = journal_adjustments::where('journal_id', $request->id)->where('isAltered', false)->first();
    
    $journal = ClientJournal::where('journal_id', $request->id)->first();
    $client = Clients::where('id', $journal->client_id)->first();
    $accounts = Accounts::where('accounts.isVisible', true)
        ->select('accounts.AccountName as Account', 'account_types.AccountType as AT', 'account_types.Category', 'accounts.id')
        ->join('account_types', 'accounts.AccountType', '=', 'account_types.id')
        ->get();
    $ats = AccountType::where('isVisible', true)->where('Category', 'Asset')->get();
    $lts = AccountType::where('isVisible', true)->where('Category', 'Liability')->get();
    $oets = AccountType::where('isVisible', true)->where('Category', 'Equity')->get();
    $ets = AccountType::where('isVisible', true)->where('Category', 'Expenses')->get();
    Log::info($groupedIncomeData);
    return view('pages.journal-audit', compact(
        'client', 'accounts', 'ats', 'lts', 'oets', 'ets', 'journal' ,'journalAsset',
        'journalExpense', 'journalExpense', 'journalLiability', 'journalOE', 'journaladjustment', 'journalIncome', 'groupedIncomeData', 'groupedExpenseData'
    ));
}

    public function AuditClientJournal(Request $request){
        if(Auth::check()){
            try {
                DB::beginTransaction();
                $references = $request['references'];
                $income = $request['incomeObj'];
                $expense = $request['expenseObj'];
                $asset = $request['assetObj'];
                $liability = $request['liabilityObj'];
                $oe = $request['oeObj'];
                $adjustment = $request['adjustmentObj'];
                $prepRef = explode('_', $references);
                $clients = Clients::where('id', $prepRef[0])->first();
                $alteredIncome = journal_income::where('journal_id', $prepRef[1])->update(['isAltered' => true]);
                $alteredExpense = journal_expense::where('journal_id', $prepRef[1])->update(['isAltered' => true]);
                $alteredasset = journal_assets::where('journal_id', $prepRef[1])->update(['isAltered' => true]);
                $alteredlias = journal_liabilities::where('journal_id', $prepRef[1])->update(['isAltered' => true]);
                $alteredoe = journal_owners_equity::where('journal_id', $prepRef[1])->update(['isAltered' => true]);
                $alteredadjustment = journal_adjustments::where('journal_id', $prepRef[1])->update(['isAltered' => true]);
                
                // //alteration 
                DB::table('journal_income_months')
                ->join('journal_incomes', 'journal_incomes.id', '=', 'journal_income_months.income_id')
                ->where('journal_incomes.journal_id', $prepRef[1])
                ->update(['journal_income_months.isAltered' => true]);

                DB::table('journal_expense_months')
                ->join('journal_expenses', 'journal_expenses.id', '=', 'journal_expense_months.expense_id')
                ->where('journal_expenses.journal_id', $prepRef[1])
                ->update(['journal_expense_months.isAltered' => true]);
                // //end of alteration
                
                foreach ($income as $key => $value) {
                    $prepKey = explode('_', $key);  
                   $incomeID = journal_income::where('journal_id', $prepRef[1])->create([
                    'journal_id' => $prepRef[1],
                    'account' =>  $key,
                    'start_date' => $value['startDate'],
                    'end_date' => $value['startDate'],
                    'client_id' => $prepRef[0]
                    ]);
                    foreach ($value['months'] as $months) {
                        $sanitizeAmount = str_replace(',','',$months['value']);
                        $prepAmount = floatval($sanitizeAmount);
                        journal_income_months::create([
                            'month' => $months['incomeMonthName'],
                            'amount' => $prepAmount,
                            'income_id' => $incomeID->id
                        ]);
                    }
                }
                foreach ($expense as $key => $value) {
                    $prepKey = explode('_', $key);  
                   $expenseID = journal_expense::where('journal_id', $prepRef[1])->create([
                    'journal_id' => $prepRef[1],
                    'account' =>  $key,
                    'start_date' => $value['startDate'],
                    'end_date' => $value['startDate'],
                    'client_id' => $prepRef[0]
                    ]);
                    foreach ($value['months'] as $months) {
                        $sanitizeAmount = str_replace(',','',$months['value']);
                        $prepAmount = floatval($sanitizeAmount);
                        journal_expense_month::create([
                            'month' => $months['expenseMonthName'],
                            'amount' => $prepAmount,
                            'expense_id' => $expenseID->id
                        ]);
                    }
                }
                foreach ($asset as $key => $value) {
                    foreach ($value['accounts'] as $assets) {
                        $sanitizeAmount = str_replace(',', '', $assets['amount']);
                        $prepAmount = floatval($sanitizeAmount);
                        journal_assets::create([
                            'client_id' => $prepRef[0],
                            'journal_id' => $prepRef[1],
                            'account' => $assets['assetAccount'],
                            'amount' => $prepAmount,
                            'asset_category' => $key,
                        ]);
                    }
                }
                foreach ($liability as $key => $value) {
                    foreach ($value['accounts'] as $lias) {
                        $sanitizeAmount = str_replace(',', '', $lias['amount']);
                        $prepAmount = floatval($sanitizeAmount);
                        journal_liabilities::create([
                            'client_id' => $prepRef[0],
                            'journal_id' => $prepRef[1],
                            'account' => $lias['liabilityAccount'],
                            'amount' => $prepAmount,
                        ]);
                    }
                }
                foreach ($oe as $key => $value) {
                    foreach ($value['accounts'] as $oes) {
                        $sanitizeAmount = str_replace(',', '', $oes['amount']);
                        $prepAmount = floatval($sanitizeAmount);
                        journal_owners_equity::create([
                            'client_id' => $prepRef[0],
                            'journal_id' => $prepRef[1],
                            'account' => $oes['oeAccount'],
                            'amount' => $prepAmount,
                        ]);
                    }
                }
                $sanitizeOC = str_replace(',', '', $adjustment['audit-owners_contribution']);
                $sanitizeOW = str_replace(',', '', $adjustment['audit-owners_withdrawal']);
                $prepOC = floatval($sanitizeOC);
                $prepOW = floatval($sanitizeOW);
                journal_adjustments::create([
                    'owners_contribution' => $prepOC,
                    'owners_withdrawal' => $prepOW,
                    'journal_id' => $prepRef[1],
                    'client_id' => $prepRef[0]
                ]);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Journal Updated',
                    'activity' => 'Updated journal entries, journal ID ' . $prepRef[0] . ' client ' . $clients->CEO.''.', '.''.$clients->CompanyName,
                    'description' => 'Updated income, expense, asset, liability, owners equity, and adjustments for journal ID ' . $prepRef[1] . ' for client'. $clients->CEO.''.', '.''.$clients->CompanyName,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'browser' => $this->getBrowserDetails($request->header('User-Agent'))['browser'] ?? null,
                    'platform' => $this->getBrowserDetails($request->header('User-Agent'))['platform'] ?? null,
                    'platform_version' => $this->getBrowserDetails($request->header('User-Agent'))['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['journal' => 'updated']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }else{
            abort(500, 'unauthorized access');
        }
    }

public function BookkeeperJournalView(Request $request){
    if(Auth::check()){
        try {
            $journal = ClientJournal::where('id', $request['journalID'])->first();
            if ($journal->journal_id === $request['journal_id']) {
                $client = Clients::where('isVisible', true)->where('id', $request['client_id'])->first();
                $income = journal_income::where('journal_id', $request['journal_id'])->where('isAltered', false)->get();
                $expense = journal_expense::where('journal_id', $request['journal_id'])->where('isAltered', false)->get();
                $assets = journal_assets::where('journal_id', $request['journal_id'])->where('isAltered', false)->get();
                $liabilities = journal_liabilities::where('journal_id', $request['journal_id'])->where('isAltered', false)->get();
                $ownersEquity = journal_owners_equity::where('journal_id', $request['journal_id'])->where('isAltered', false)->get();
                $adjustments = journal_adjustments::where('journal_id', $request['journal_id'])->where('isAltered', false)->first();
                $netIncome = 0;
                
                $this->fpdf->AddPage();
                $this->fpdf->SetFont('Arial', 'B', 12);
                $this->fpdf->SetY(2);
                $this->fpdf->Cell(0, 12, "Statement of Financial Position", 0, 1, 'C');

                $this->fpdf->SetY(10);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyName)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyName);

                $this->fpdf->SetY(15);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyAddress)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyAddress);

                $this->fpdf->SetY(23);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth("Statement of Financial Operation")) / 2);
                $this->fpdf->Cell(0, 10, "Statement of Financial Operation");

                $this->fpdf->SetFont('Arial', '', 8);
                $this->fpdf->SetY(30);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "For the year ended December 31");
                $this->fpdf->Line(30, 37, 210-10, 37);
                //expenses
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY(40);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, "Revenues");
                $this->fpdf->SetFont('Arial', '', 10);
                $yPosition = 45;
                $pageWidth = $this->fpdf->GetPageWidth();
                $rightMargin = 10;
                $leftMargin = 0;
                $incomeGrandTotal = 0;
                foreach ($income as $key => $value) {
                    $incomeTotal = 0;  
                    $preparedAccount = explode('_', $value['account']);
                    $incomeMonths = journal_income_months::where('income_id', $value->id)->get();
                    foreach ($incomeMonths as $incomeMonth) {
                        $incomeTotal += (float) $incomeMonth->amount;
                    }
                    $incomeGrandTotal += $incomeTotal;
                    $this->fpdf->SetY($yPosition);
                    $this->fpdf->SetX(35);
                    $this->fpdf->Cell(0, 10, $preparedAccount[1]);
                    $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                    $this->fpdf->Cell(0, 10, number_format($incomeTotal, 2), 0, 1, 'R');
                    $yPosition += 5;
                }
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($incomeGrandTotal, 2), 'T', 0, 'R');


                //expenses (costs)
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition+10);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Less: Direct Cost');
                $this->fpdf->SetFont('Arial', '', 10);
                $yPosition += 15;
                $ldcTotal = 0;
                foreach ($expense as $key => $value) {
                    $total = 0;
                    $preparedAccount = explode('_', $value['account']);
                    if(strpos($value['account'], 'Less Direct Cost') !== false){
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $preparedAccount[1]);
                        $expenseMonths = journal_expense_month::where('expense_id', $value->id)->get();
                        foreach ($expenseMonths as $expenseMonth) {
                            $total += (float) $expenseMonth->amount;
                        }
                        $ldcTotal += $total;
                        $this->fpdf->SetY($yPosition+3);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 6, number_format($total, 2), '', 0, 'R');
                    }
                    $yPosition += 5;
                }
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Direct Cost');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($ldcTotal, 2), 'T', 0, 'R');


                //total engineering costs
                $totalGrossIncome = 0;
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Gross Income from Engineering Services');
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                if($ldcTotal < $incomeGrandTotal){
                $this->fpdf->Cell(0, 10, number_format($incomeGrandTotal - $ldcTotal, 2), 'T', 0, 'R');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->Cell(0, 10, 'Total Gross Income');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, number_format($incomeGrandTotal - $ldcTotal, 2), '', 0, 'R');
                $totalGrossIncome = $incomeGrandTotal - $ldcTotal;
                }
                else{
                $this->fpdf->Cell(0, 10, number_format($ldcTotal - $incomeGrandTotal, 2), 'T', 0, 'R');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->Cell(0, 10, 'Total Gross Income');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, number_format($ldcTotal - $incomeGrandTotal, 2), '', 0, 'R');
                $totalGrossIncome = $ldcTotal - $incomeGrandTotal;
            }
                
                //operating expense
                $yPosition += 40;
                $loetotal = 0;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition-5);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Less: Operating Expenses');
                $this->fpdf->SetFont('Arial', '', size: 10);
                foreach ($expense as $key => $value) {
                    $total = 0;
                    $preparedAccount = explode('_', $value['account']);
                    if(strpos($value['account'], 'Operating Expenses') !== false){
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $preparedAccount[1]);
                        $expenseMonths = journal_expense_month::where('expense_id', $value->id)->get();
                        foreach ($expenseMonths as $expenseMonth) {
                            $total += (float) $expenseMonth->amount;
                        }
                        $loetotal += $total;
                        $this->fpdf->SetY($yPosition+3);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 6, number_format($total, 2), '', 0, 'R');
                    }
                    $yPosition += 5;
                }
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Operating Expense');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($loetotal, 2), 'T', 0, 'R');
                // $this->fpdf->Cell(0, 10, number_format($ldcTotal - $incomeGrandTotal), 'T', 0, 'R');

                //net income 
                $yPosition += 15;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Net Income');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($totalGrossIncome - $loetotal, 2), 'T', 0, 'R');
                $netIncome = $totalGrossIncome - $loetotal;
                $yPosition += 15;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition + 5);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Certified True & Correct');
                // $this->fpdf->SetY($yPosition + 15);
                // $this->fpdf->SetX(30);
                $text = 'Rogelio O. Mangandam, Jr.';
                $textWidth = $this->fpdf->GetStringWidth($text) + 2;
                $this->fpdf->SetY($yPosition + 15);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, $text, 'B', 0, '');
                $this->fpdf->SetFont('Arial', '', size: 10);
                $this->fpdf->SetY($yPosition + 23);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "Proprietor");
                $this->fpdf->SetY($yPosition + 30);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "TIN: 291-273-180-000");
                //end of statement of financial operation


                //start of statement of financial position
                $yPosition = 45;
                $this->fpdf->AddPage();
                $this->fpdf->SetFont('Arial', 'B', 10);

                $this->fpdf->SetY(10);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyName)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyName);

                $this->fpdf->SetY(15);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyAddress)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyAddress);

                $this->fpdf->SetY(23);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth("Statement of Financial Position")) / 2);
                $this->fpdf->Cell(0, 10, "Statement of Financial Position");

                $this->fpdf->SetFont('Arial', '', 8);
                $this->fpdf->SetY(30);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "as of December 31");
                $this->fpdf->Line(30, 37, 210-10, 37);


                //assets
                //Current Assets
                $currentAssetsTotal = 0;
                $fixedAssetsTotal = 0;
                $nonCurrentAssetsTotal = 0;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "Current Assets");
                $this->fpdf->SetFont('Arial', '', 10);

                foreach ($assets as $value) {
                    if ($value['asset_category'] === 'Current Asset') {
                        $yPosition += 5;
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $value['account']);
                        $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                        $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                        $currentAssetsTotal += (float)$value['amount'];
                    }
                }

                $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $yPosition += 6;
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Current Assets');
                $this->fpdf->SetY($yPosition + 2);
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($currentAssetsTotal, 2));
                $this->fpdf->Line(30, $yPosition + 7, 210-10, $yPosition + 7);
                //Current Assets

                //non-current assets
                $yPosition += 10;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "Non-Current Assets");
                $this->fpdf->SetFont('Arial', '', 10);

                foreach ($assets as $value) {
                    if ($value['asset_category'] === 'Non-Current Assets') {
                        $yPosition += 5;
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $value['account']);
                        $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                        $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                        $nonCurrentAssetsTotal += (float)$value['amount'];
                    }
                }

                $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $yPosition += 6;
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Non-Current Assets');
                $this->fpdf->SetY($yPosition + 2);
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($nonCurrentAssetsTotal, 2));
                $this->fpdf->Line(30, $yPosition + 7, 210-10, $yPosition + 7);
                //non-current assets

                //fixed assets
                $yPosition += 10;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "Fixed Assets");
                $this->fpdf->SetFont('Arial', '', 10);

                foreach ($assets as $value) {
                    if ($value['asset_category'] === 'Fixed Assets') {
                        $yPosition += 5;
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $value['account']);
                        $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                        $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                        $fixedAssetsTotal += (float)$value['amount'];
                    }
                }

                // $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                // $this->fpdf->SetFont('Arial', 'B', 10);
                // $yPosition += 6;
                // $this->fpdf->SetY($yPosition);
                // $this->fpdf->SetX(35);
                // $this->fpdf->Cell(0, 10, 'Total Fixed Assets');
                // $this->fpdf->SetY($yPosition + 2);
                // $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                // $this->fpdf->Cell(0, 6, number_format($fixedAssetsTotal, 2));
                // $this->fpdf->Line(30, $yPosition + 7, 210-10, $yPosition + 7);
                $yPosition += 10;
                $totalAssets = $currentAssetsTotal + $fixedAssetsTotal + $nonCurrentAssetsTotal;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Line(30, $yPosition + 3, 210-10, $yPosition + 3);
                $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Total Assets');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 10, number_format($totalAssets, 2));
                $this->fpdf->Line(30, $yPosition + 8, 210-10, $yPosition + 8);

                //liabilities
                $yPosition += 10;
                $liabilitiesTotal = 0;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Current Liabilities');

                foreach ($liabilities as $value) {
                    // $yPosition += 5;
                    // $this->fpdf->SetFont('Arial', '', 10);
                    // $this->fpdf->SetY($yPosition);
                    // $this->fpdf->SetX(35);
                    // $this->fpdf->Cell(0, 10, $value['account']);
                    // $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                    // $this->fpdf->Cell(0, 10, txt: number_format($value['amount'], 2));
                    $liabilitiesTotal += (float) $value['amount'];
                }
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 10, number_format($liabilitiesTotal,2));
                //liabilities

                //owners equity/net worth
                $yPosition += 10;
                $ownersEquityTotal = 0;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, "Owner"."'"."s Equity / Net Worth");
                //owners equity/net worth
                foreach ($ownersEquity as $value) {
                    $yPosition += 5;
                    $this->fpdf->SetFont('Arial', '', 10);
                    $this->fpdf->SetY($yPosition);
                    $this->fpdf->SetX(35);
                    $this->fpdf->Cell(0, 10, $value['account']);
                    $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                    $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                    $ownersEquityTotal += (float) $value['amount'];
                }

                $yPosition += 10;
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Add: Net Increase to Capital');
                $this->fpdf->SetY($yPosition + 5);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Additional Capital');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($adjustments->owners_contribution, 2));
                $this->fpdf->SetFont('Arial', '', 10);
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Net Income');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($netIncome, 2));
                $this->fpdf->Line(30, $yPosition + 18, 210-10, $yPosition + 18);
                $this->fpdf->SetFont('Arial', '', 10);
                $yPosition += 10;
                $this->fpdf->SetY($yPosition + 6);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Appraisal Capital');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($netIncome + $adjustments->owners_withdrawal, 2));
                $appraisalCapital = $netIncome + $adjustments->owners_withdrawal;
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', '', 10);
                $this->fpdf->Cell(0, 10, 'Less: Drawings');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($adjustments->owners_withdrawal, 2));
                $this->fpdf->SetY($yPosition + 15);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', '', 10);
                $this->fpdf->Cell(0, 10, 'Capital, end');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($appraisalCapital - $adjustments->owners_withdrawal, 2));

                $yPosition += 10;
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, 'Total Liabilities & Capital');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 10, number_format($liabilitiesTotal + $ownersEquityTotal, 2));
                $this->fpdf->Line(30, $yPosition + 13, 210-10, $yPosition + 13);
                $this->fpdf->Line(30, $yPosition + 18, 210-10, $yPosition + 18);

                $yPosition += 15;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition + 5);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Certified True & Correct');
                
                $text = 'Rogelio O. Mangandam, Jr.';
                $textWidth = $this->fpdf->GetStringWidth($text) + 2;
                $this->fpdf->SetY($yPosition + 15);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, $text, 'B', 0, '');
                $this->fpdf->SetFont('Arial', '', size: 10);
                $this->fpdf->SetY($yPosition + 23);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "Proprietor");
                $this->fpdf->SetY($yPosition + 30);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "TIN: 291-273-180-000");
                $this->fpdf->Output('I', 'example.pdf');
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'View Client Journal',
                'activity' => 'View Client Journal',
                'description' => "View Client Journal $client->CEO - $client->CompanyName",
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'browser' => $browserDetails['browser'] ?? null,
                'platform' => $browserDetails['platform'] ?? null,
                'platform_version' => $browserDetails['platform_version'] ?? null,
            ]);
                exit;
            }else{
                return response()->json(['message' => 'Incorrect PIN'], 400);
            }
        } catch (\Throwable $th) {
            response()->json('something went wrong');
            throw $th;
        }
    }else{
        dd('unauthorized access');
    }
}

}
