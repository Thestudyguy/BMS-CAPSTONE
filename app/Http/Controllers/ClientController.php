<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientRepresentative;
use App\Models\Clients;
use App\Models\CompanyProfile;
use App\Models\services;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
    // public function CreateNewClient(Request $request)
    // {
    //     if (Auth::check()) {
    //         $client = $request->all();
    //         $profile = $request->file('profile');
    //         $validateClient = Validator::make($request->all(), [
    //             'CompanyName' => 'required|string|max:255|unique:clients,CompanyName',
    //             'CompanyAddress' => 'required|string|max:255',
    //             'TIN' => 'required|string|max:255|unique:clients,TIN',
    //             'CompanyEmail' => 'required|string|email|max:255|unique:clients,CompanyEmail',
    //             'CEO' => 'required|string|max:255',
    //             'CEODateOfBirth' => 'required|date',
    //             'CEOContactInformation' => 'required|string|max:255|unique:clients,CEOContactInformation',
    //         ]);
    //         $validateClientRep = Validator::make($request->all(), [
    //         'CompanyRepresented' => 'nullable|exists:clients,id',
    //         'RepresentativeName' => 'required|string|max:255|unique:client_representatives,RepresentativeName',
    //         'RepresentativeContactInformation' => 'required|string|max:255|unique:client_representatives,RepresentativeContactInformation',
    //         'RepresentativeDateOfBirth' => 'required|date',
    //         'RepresentativePosition' => 'required|string|max:255',
    //         'RepresentativeAddress' => 'required|string|max:255',
    //         ]);
    //         if ($validateClientRep->fails() || $validateClient->fails()) {
    //             return response()->json(['error' => 'Validation Failed', 'details' => $clientRepValidator->errors()], 422);
    //         }
    //         if ($profile) {
    //             $profilePath = $profile->store('profiles', 'public');
    //             Log::info("Profile stored at: " . $profilePath);
    //         }
    //         return response()->json(['success' => 'Client and profile received successfully'], 200);
    //     } else {
    //         return response()->json(['error' => 'Not authorized'], 403);
    //     }
    // }

    public function CreateNewClient(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authorized'], 403);
        }
    
        // Validation rules
        $validatedData = $request->validate([
            // Client data validation
            'CompanyName' => 'required|string|max:255',
            'CompanyAddress' => 'required|string|max:255',
            // 'TIN' => 'required|string|max:50',
            'CompanyEmail' => 'required|email|max:255',
            'CEO' => 'required|string|max:255',
            'CEODateOfBirth' => 'required|date',
            'CEOContactInformation' => 'required|string|max:255',
    
            // Representative data validation
            'RepresentativeName' => 'required|string|max:255',
            'RepresentativeContactInformation' => 'required|string|max:255',
            'RepresentativeDateOfBirth' => 'required|date',
            'RepresentativePosition' => 'required|string|max:255',
            'RepresentativeAddress' => 'required|string|max:255',
    
            // Profile image validation
            'profile' => 'nullable|file|image|max:2048',  // Optional, but if provided, should be an image
        ]);
    
        try {
            // Start database transaction
            DB::beginTransaction();
    
            // Save client data to the 'clients' table
            $client = new Clients();
            $client->CompanyName = $validatedData['CompanyName'];
            $client->CompanyAddress = $validatedData['CompanyAddress'];
            $client->TIN = '123123';
            $client->CompanyEmail = $validatedData['CompanyEmail'];
            $client->CEO = $validatedData['CEO'];
            $client->CEODateOfBirth = $validatedData['CEODateOfBirth'];
            $client->CEOContactInformation = $validatedData['CEOContactInformation'];
            $client->dataEntryUser = Auth::user()->id; // assuming the user ID as dataEntryUser
            $client->save();
    
            // Save representative data to the 'client_representatives' table
            $representative = new ClientRepresentative();
            $representative->CompanyRepresented = $client->id;
            $representative->RepresentativeName = $validatedData['RepresentativeName'];
            $representative->RepresentativeContactInformation = $validatedData['RepresentativeContactInformation'];
            $representative->RepresentativeDateOfBirth = $validatedData['RepresentativeDateOfBirth'];
            $representative->RepresentativePosition = $validatedData['RepresentativePosition'];
            $representative->RepresentativeAddress = $validatedData['RepresentativeAddress'];
            $representative->dataEntryUser = Auth::user()->id;
            $representative->save();
    
            // If profile image is provided, save it to 'company_profiles' table
            if ($request->hasFile('profile')) {
                $profile = $request->file('profile');
                $profilePath = $profile->store('profiles', 'public');  // Save the profile image in the 'public/profiles' directory
    
                $companyProfile = new CompanyProfile();
                $companyProfile->company = $client->id;
                $companyProfile->image_path = $profilePath;
                $companyProfile->dataUserEntry = Auth::user()->id;
                $companyProfile->save();
            }
    
            // Commit the transaction after successful saving
            DB::commit();
    
            // Return success response
            return response()->json(['success' => 'Client, representative, and profile saved successfully'], 200);
    
        } catch (\Exception $e) {
            // Rollback transaction in case of an error
            DB::rollBack();
    
            // Log the error for debugging
            Log::error('Error creating new client: ' . $e->getMessage());
    
            // Return error response
            return response()->json(['error' => 'An error occurred while saving data'], 500);
        }
    }
    
}
