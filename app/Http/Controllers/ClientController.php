<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientRepresentative;
use App\Models\Clients;
use App\Models\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
    if (Auth::check()) {
        $company = $request['company'];
        $representative = $request['rep'];
        $service = $request['service'];
        Log::info($representative['RepresentativeName']);
        $clientValidator = Validator::make($request->all(), [
            'company.CompanyName' => 'required|string|max:255|unique:clients,CompanyName',
            'company.CompanyAddress' => 'required|string|max:255',
            'company.TIN' => 'required|string|max:255|unique:clients,TIN',
            'company.CompanyEmail' => 'required|string|email|max:255|unique:clients,CompanyEmail',
            'company.CEO' => 'required|string|max:255',
            'company.CEODateOfBirth' => 'required|date',
            'company.CEOContactInformation' => 'required|string|max:255|unique:clients,CEOContactInformation',
        ]);

        if ($clientValidator->fails()) {
            return response()->json(['error' => 'Client data validation failed', 'details' => $clientValidator->errors()], 422);
        }

       
        
        $newClient = Clients::create([
            'CompanyName' => $company['CompanyName'],
            'CompanyAddress' => $company['CompanyAddress'],
            'TIN' => $company['TIN'],
            'CompanyEmail' => $company['CompanyEmail'],
            'CEO' => $company['CEO'],
            'CEODateOfBirth' => $company['CEODateOfBirth'],
            'CEOContactInformation' => $company['CEOContactInformation'],
            'dataEntryUser' => Auth::user()->id
        ]);
            $this->createClientRep($newClient->id, $representative);
        Log::info($newClient->id);
        return response()->json(['success' => 'All validations passed and data processed successfully'], 200);
    } else {
        return response()->json(['error' => 'Not authorized'], 403);
    }
}

    private function createClientRep($clientID, $rep){
        $clientRepValidator = Validator::make($rep, [
            'CompanyRepresented' => 'nullable|exists:clients,id',
            'RepresentativeName' => 'required|string|max:255|unique:client_representatives,RepresentativeName',
            'RepresentativeContactInformation' => 'required|string|max:255|unique:client_representatives,RepresentativeContactInformation',
            'RepresentativeDateOfBirth' => 'required|date',
            'RepresentativePosition' => 'required|string|max:255',
            'RepresentativeAddress' => 'required|string|max:255',
        ]);

        if ($clientRepValidator->fails()) {
            return response()->json(['error' => 'Client representative data validation failed', 'details' => $clientRepValidator->errors()], 422);
        }
         ClientRepresentative::create([
               'CompanyRepresented' => $clientID,
               'RepresentativeName' => $rep['RepresentativeName'],
               'RepresentativeContactInformation' => $rep['RepresentativeContactInformation'],
               'RepresentativeDateOfBirth' => $rep['RepresentativeDateOfBirth'],
               'RepresentativePosition' => $rep['RepresentativePosition'],
               'RepresentativeAddress' => $rep['RepresentativeAddress'],
               'dataEntryUser' => Auth::user()->id
            ]);
        Log::info($clientID);
        Log::info($rep['RepresentativeName']);
    }


   
}
