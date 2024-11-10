<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailClientServices;
use App\Models\AccountDescription;
use App\Models\Accounts;
use App\Models\AccountType;
use App\Models\Billings;
use App\Models\ClientBilling;
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
class ClientController extends Controller
{
    public function returnClientData()
    {
        if (Auth::check()) {
            try {
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
                $profilePath = $profile->store('profiles', 'public');
                $companyProfile = new CompanyProfile();
                $companyProfile->company = $client->id;
                $companyProfile->image_path = $profilePath;
                $companyProfile->dataUserEntry = Auth::user()->id;
                $companyProfile->save();
            }
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
                $clientId = $request->input('client_id');
                foreach ($request['services'] as $services) {
                    $serviceName = $services['serviceName'];

                    $isServiceExisting = ClientServices::where('isVisible', true)
                    ->where('Client', $clientId)
                    ->where('ClientService', $serviceName)
                    ->first();

                    if($isServiceExisting){
                        return response()->json(['Conflict' => "Service '{$serviceName}' already exists for this client."], 409);
                    }

                    $file = $services['serviceFile'] ?? null;

                    $fileName = 'none';
                    $mimeType = null;
                    $size = null;
                    $realPath = null;

                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $fileName = $file->getClientOriginalName();
                        $mimeType = $file->getClientMimeType();
                        $size = $file->getSize();
                        $realPath = $file->getRealPath();
                    }

                    Log::info("{
                        Service: {$services['serviceName']} \n
                        Price: {$services['servicePrice']} \n
                        File: $fileName
                    }");

                    

                    ClientServices::create([
                        'Client' => $clientId,
                        'ClientService' => $services['serviceName'],
                        'ClientServiceProgress' => 'Pending',
                        'getClientOriginalName' => $fileName,
                        'getClientMimeType' => $mimeType,
                        'getSize' => $size,
                        'getRealPath' => $realPath,
                        'dataEntryUser' => Auth::user()->id,
                        'isVisible' => true,
                    ]);
                    // $this->MailClientServices($clientId);
                }
            } catch (\Throwable $th) {
                Log::error($th);
                throw $th;
            }
        } else {
            dd('Unauthorized Access');
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

    public function ClientJournal(Request $request)
    {
        if (Auth::check()) {
            $client = Clients::where('id', $request->id)->first();
            $journals = journal_income::where('client_id', $request->id)->get();
            return view('pages.client-journal', compact('client', 'journals'));
        } else {
            dd('unauthorize access');
        }
    }


    public function ClientBilling(Request $request)
    {
            if (Auth::check()) {
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
                $result[$clientId]['Service'][$service->ParentService]['parent_service_id'][$service->ParentServiceID] = [
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
            // Log::info(json_encode($ads, JSON_PRETTY_PRINT));
            response()->json(['services' => $result, 'current_date' => $currentDate, 'ads' => $ads]);
            return view('pages.billings', compact('clientId', 'result', 'systemProfile', 'client', 'currentDate', 'ads', 'uniqueId'));
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
                            'income_id' => $jee->id,
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
                Log::info($adjustments['owners_contribution']);
                $preparedAdjustmentAmountoc = str_replace(',', '', $adjustments['owners_contribution']);
                $preparedAdjustmentAmountow = str_replace(',', '', $adjustments['owners_withdrawal']);
                journal_adjustments::create([
                    'client_id' => $client,
                    'owners_contribution' => $preparedAdjustmentAmountoc,
                    'owners_withdrawal' => $preparedAdjustmentAmountow,
                    'journal_id' => $uniqueId
                ]);
                return response()->json(['journal' => 'new journal entry saved successfully']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        else{
            dd('unauthorize access');
        }
    }


}
