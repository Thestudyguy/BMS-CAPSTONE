<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function CreateNewClient(Request $request){
        if(Auth::check()){
            try {
                //validate client
                $validateClient = $request->validate([
                    'CompanyName' => 'required|string|max:255',
                    'CompanyAddress' => 'required|string|max:255',
                    'TIN' => 'required|string|max:255|unique:clients,TIN',
                    'CompanyEmail' => 'required|string|email|max:255|unique:clients,CompanyEmail',
                    'CEO' => 'required|string|max:255',
                    'CEODateOfBirth' => 'required|date',
                    'CEOContactInformation' => 'required|string|max:255',
                    'dataEntryUser' => 'required|string|max:255',
                ]);
                //validate client service
                $validateClientService = $request->validate([
                    'Client' => 'nullable|exists:clients,id',
                    'ClientService' => 'required|string|max:255',
                    'ClientServiceProgress' => 'required|string|max:255',
                    'dataEntryUser' => 'required|string|max:255',
                ]);

                //validate rep
                $validateClientRep = $request->validate([
                    'CompanyRepresented' => 'nullable|exists:clients,id',
                    'RepresentativeName' => 'required|string|max:255',
                    'RepresentativeContactInformation' => 'required|string|max:255',
                    'RepresentativeDateOfBirth' => 'required|date',
                    'RepresentativePosition' => 'required|string|max:255',
                    'RepresentativeAddress' => 'required|string|max:255',
                    'dataEntryUser' => 'required|string|max:255',
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('not authorized');
        }
    }
}
