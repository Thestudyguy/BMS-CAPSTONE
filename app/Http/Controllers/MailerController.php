<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailClientBillingStatement;
use App\Mail\JournalPINRequest;
use App\Models\BillingAddedDescriptions;
use App\Models\BillingDescriptions;
use App\Models\Billings;
use App\Models\ClientAdditionalDescriptions;
use App\Mail\MailClientServices;
use App\Models\AccountDescription;
use App\Models\ClientBilling;
use App\Models\ClientBillingService;
use App\Models\ClientBillingSubService;
use App\Models\ClientJournal;
use App\Models\Clients;
use App\Models\SystemProfile;
use App\Models\User;
use \Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class MailerController extends Controller
{
    //
    public function MailClientServices(Request $request){
        if (Auth::check()) {
            $sendTo = 'lagrosaedrian06@gmail.com';

            Mail::to($sendTo)->send(new MailClientServices('test', 'test email'));
        }else{
            dd('Unauthorized Access');
            
        }
    }

    public function NewClientBilling(Request $request){
        if(Auth::check()){
            try {
                $client = $request['refID'];
                $service = $request['serviceObj'];
                $subService = $request['subServiceObj'];
                $descriptions = $request['accountDescriptions'];
                $billingID = $request['billingId'];
                $dueDate = $request['dueDate'];
                
                foreach ($service as $key => $items) {
                    foreach ($items as $key => $value) {
                        foreach ($value as $key => $values) {
                            $billingService = ClientBillingService::create([
                                'client_id' => $client,
                                'billing_id' => $billingID,
                                'service' => $values['service_id'],
                                'account' => $values['service_name'],
                                'amount' => (float) $values['service_price']
                            ]);
                        }
                    }
                }
                foreach ($subService as $key => $value) {
                    foreach ($value as $key => $values) {
                        foreach ($values as $key => $subServices) {
                            ClientBillingSubService::create([
                                'client_id' => $client,
                                'billing_id' => $billingID,
                                'sub_service' => $subServices['sub_service_id'],
                                'service' => $billingService->id,
                                'account' => $subServices['sub_service_name'],
                                'amount' => (float) $subServices['sub_service_price']
                            ]);
                        }
                    }
                }
                
                if (!empty($descriptions)) {
                    Log::info($descriptions);
                    foreach ($descriptions as $descriptionGroup) {
                        foreach ($descriptionGroup as $descriptionArray) {
                            foreach ($descriptionArray as $description) {
                                
                                if (isset($description['isAdded']) && $description['isAdded'] === 'true') {
                                    BillingAddedDescriptions::create([
                                        'client_id' => $client,
                                        'billing_id' => $billingID,
                                        'description' => $description['account_id'],
                                        'account' => $description['Account'],
                                        'amount' => (float) $description['price'],
                                        'category' => 'not necessary'
                                    ]);
                                } else {
                                    BillingDescriptions::create([
                                        'client_id' => $client,
                                        'billing_id' => $billingID,
                                        'description' => $description['account_id'],
                                        'account' => $description['Account'],
                                        'amount' => (float) $description['price'],
                                        'category' => 'not necessary'
                                    ]);
                                }
                            }
                        }
                    }
                }
                $billing = Billings::create([
                    'client_id' => $client,
                    'billing_id' => $billingID,
                    'due_date' => $dueDate
                ]);
                $this->NotifyClientBilling($billing);
                return response()->json(['billing' => 'billing successfully saved']);                
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }

    private function NotifyClientBilling($billing){
        try {
            Log::info($billing['billing_id']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function MailClientBillingStatement($id){
        if (Auth::check()) {
            $clientId = $id;
            $sendTo = Clients::where('id', $clientId)->pluck('CompanyEmail')->first();
            Log::info($id);
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
                'account_descriptions.Category as adCategory',
                'services_sub_tables.ServiceRequirements',
                'services.Category', 'services.Service'
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
                    's.id as ServiceID',
                    'cs_parent.id as ParentServiceID'
                )
                ->get();

            $result = [];

            foreach ($clientServicesData as $service) {
                $result[$clientId]['Service'][$service->ParentService] = [
                    'sub_service' => [],
                ];
            
                $subServices = DB::table('client_services as cs_sub')
                    ->join('services_sub_tables as ss', 'cs_sub.ClientService', '=', 'ss.ServiceRequirements')
                    ->where('cs_sub.Client', $clientId)
                    ->where('ss.BelongsToService', $service->ServiceID)
                    ->where('cs_sub.isVisible', true)
                    ->select(
                        'ss.ServiceRequirements',
                        'ss.id as SubServiceID',
                        'cs_sub.ClientService as SubServiceName'
                    )
                    ->get();

                foreach ($subServices as $subService) {
                    $result[$clientId]['Service'][$service->ParentService]['sub_service'][$subService->ServiceRequirements] = [
                        'account_descriptions' => [],
                    ];

                    $accountDescriptions = AccountDescription::where('account', $subService->SubServiceID)
                        ->where('isVisible', true)
                        ->get();

                    foreach ($accountDescriptions as $accountDescription) {
                        $result[$clientId]['Service'][$service->ParentService]['sub_service'][$subService->ServiceRequirements]['account_descriptions'][] = [
                            'Category' => $accountDescription->Category,
                            'Description' => $accountDescription->Description,
                            'TaxType' => $accountDescription->TaxType,
                            'FormType' => $accountDescription->FormType,
                            'Price' => $accountDescription->Price,
                        ];
                    }
                }
            }
            Mail::to($sendTo)->send(new MailClientBillingStatement($clientId,$result, $systemProfile, $client, $currentDate, $ads, $sendTo));
            // return view('pages.billings', compact('clientId', 'result', 'systemProfile', 'client', 'currentDate', 'ads'));
            return response()->json(['response' => 'email sent']);
        } else {
            dd('unauthorized access');
        }
    }
    
    public function SendJournalPINRequest($ids){
        if(Auth::check()){
            try {
                $client = Clients::where('id', explode('_', $ids)[1])->first();
                $sendTo = User::where('id', explode('_', $ids)[2])->first();
                $requestorFN = Auth::user()->FirstName;
                $requestorLN = Auth::user()->LastName;
                $journal = ClientJournal::where('id', explode('_', $ids)[0])->first();
                // Log::info(
                //     "$requestor \n
                //         Requesting for client's $client->CompanyName, $client->CEO Journal PIN $journal->journal_id
                //     "
                // );
                // Mail::to($sendTo->Email)->send(new JournalPINRequest($client, $requestorFN, $requestorLN, $journal));
                Mail::to($sendTo->Email)->send(new JournalPINRequest($client, $requestorFN, $requestorLN, $journal));
                return response()->json(['mail' => 'Request Sent']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{

        }
    }
}
