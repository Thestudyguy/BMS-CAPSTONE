<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailClientServices;
use App\Models\Accounts;
use App\Models\ClientRepresentative;
use App\Models\Clients;
use App\Models\ClientServices;
use App\Models\CompanyProfile;
use App\Models\services;
use App\Models\SystemProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Mail;
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
    

    public function ClientServices(Request $request) {
        if (Auth::check()) {
            try {
                $clientId = $request->input('client_id');
                foreach ($request['services'] as $services) {
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
                    $this->MailClientServices($clientId);
                }
            } catch (\Throwable $th) {
                Log::error($th);
                throw $th;
            }
        } else {
            dd('Unauthorized Access');
        }
    }
    
    public function viewClientProfile(Request $request){
        if(Auth::check()){
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
    
    public function ClientJournal(Request $request){
        if(Auth::check()){
            Log::info($request->id);
            $client = Clients::where('id', $request->id)->first();
            return view('pages.client-journal', compact('client'));
        }else{
            dd('unauthorize access');
        }
    }

    public function ClientBilling(Request $request){
        if(Auth::check()){
            Log::info($request->id);
            $client = Clients::where('id', $request->id)->first();
            $systemProfile = SystemProfile::all();
            $services = services::where('isVisible', true)->get();
            return view('pages.billings', compact('client', 'services', 'systemProfile'));
        }else{
            dd('unauthorize access');
        }
    }

    public function ClientJournalForm(Request $request){
        if(Auth::check()){
            Log::info($request->id);
            $client = Clients::where('id', $request->id)->first();
            $accounts = Accounts::where('accounts.isVisible', true)
            ->select('accounts.AccountName as Account', 'account_types.AccountType as AT', 'account_types.Category', 'accounts.id')
            ->join('account_types', 'accounts.AccountType', '=', 'account_types.id')
            ->get();
            return view('pages.client-journal-form', compact('client', 'accounts'));
        }else{
            dd('unauthorize access');
        }
    }

    private function MailClientServices($clientID){
        try {
            Log::info($clientID);
        $services = ClientServices::where('Client',$clientID)->get();
        $client = Clients::where('id', $clientID)->pluck('CompanyEmail')->first();
        foreach ($services as $service) {
            Log::info($service->ClientService);
            if(!$service->isClientNotified){
                Mail::to($client)->send(new MailClientServices($service->ClientService, testemail: 'asd'));
                
            }
        }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
