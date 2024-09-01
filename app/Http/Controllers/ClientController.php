<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class ClientController extends Controller
{
    public function CreateNewClient(Request $request)
    {
        if (Auth::check()) {
            try {
                Log::info($request);
                return;
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
                $validateClientService = $request->validate([
                    'Client' => 'nullable|exists:clients,id',
                    'ClientService' => 'required|string|max:255',
                    'ClientServiceProgress' => 'required|string|max:255',
                    'dataEntryUser' => 'required|string|max:255',
                ]);

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
        } else {
            dd('not authorized');
        }
    }

    public function returnClientData()
    {
        if (Auth::check()) {
            try {
                $services = services::where('isVisible', true)->get();
                return view('pages.clients', compact('services'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }
}
