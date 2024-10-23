<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailClientBillingStatement;
use App\Mail\MailClientServices;
use App\Models\AccountDescription;
use App\Models\Clients;
use App\Models\SystemProfile;
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

    public function MailCLientBilling(Request $request){
        if(Auth::check()){
            try {
                $accounts = $request['accountDescriptions'];
                $subServices = $request['subServiceObj'];
                $services = $request['serviceObj'];
                foreach ($services as $service => $serviceData) {
                    foreach ($serviceData as $services) {
                        foreach ($services as $service) {
                            Log::info($service);
                        }
                    }
               }
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
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
}
