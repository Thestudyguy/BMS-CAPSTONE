<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\services;
use App\Models\ServicesSubTable;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function returnServices()
    {
        if (Auth::check()) {
            try {
                $services = services::where('isVisible', true)->get();
                return view('pages.external-services', compact('services'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }

    public function returnSubServicesById($id)
    {
        if (Auth::check()) {
            try {
                $serviceSubServices = ServicesSubTable::where('BelongsToService', $id)
                    ->where('isVisible', true)->get();
                return response()->json(['services' => $serviceSubServices]);
            } catch (\Throwable $th) {
                response()->json(['error' => $th]);
                throw $th;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }

    public function NewService(Request $request)
    {
        if (Auth::check()) {
            try {
                $validator = Validator::make($request->all(), [
                    'Service' => 'required|string|max:255',
                    'Category' => 'required|string|max:255',
                    'Price' => 'required|min:0',
                ]);
                if ($validator->fails()) {
                    return response()->json(['validation_errors' => $validator->errors()], 422);
                }
                $existingService = services::where('Service', $request['Service'])->first();
                if ($existingService) {
                    return response()->json([
                        'error' => 'Service already exists',
                    ], 409);
                }
                services::create([
                    'Service' => $request['Service'],
                    'Category' => $request['Category'],
                    'Price' => $request['Price'],
                    'dataEntryUser' => Auth::user()->id
                ]);
                return response()->json(['middleware_success' => 'Service created successfully']);
            } catch (\Throwable $th) {
                return response()->json(['error' => $th->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }

    public function removeService($id)
    {
        if (Auth::check()) {
            try {
                services::where('id', $id)->update(['isVisible' => false]);
                return response()->json(['serviceRemoved' => 'removed'], 200);
            } catch (\Throwable $th) {
                response()->json(['error' => "Something went wrong " . $th]);
                throw $th;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }


    public function UpdateService(Request $request)
    {
        if (Auth::check()) {
            try {
                $price = str_replace(',', '', $request->Price);
                $validator = Validator::make($request->all(), [
                    'Service' => 'required|string|max:255',
                    'Price' => 'required|min:0',
                    'id' => 'required|exists:services,id'
                ]);
                if ($validator->fails()) {
                    return response()->json(['validation_errors' => $validator->errors()], 422);
                }
                $existingService = Services::where('Service', $request->Service)
                    ->where('id', '!=', $request->id)
                    ->first();

                if ($existingService) {
                    return response()->json(['error' => 'Service already exists'], 409);
                }
                services::where('id', $request->id)
                    ->update([
                        'Service' => $request->Service,
                        'Price' => $price
                    ]);
                return response()->json(['update' => 'Service Updated Successfully'], 200);
            } catch (\Throwable $th) {
                response()->json(['error' => "Something went wrong " . $th]);
                throw $th;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }

    public function returnSubServices($id)
    {
        if (Auth::check()) {
            $subServices = ServicesSubTable::where('isVisible', true)
            ->where('BelongsToService', $id)->select(
                'ServiceRequirements', 'ServiceRequirementPrice', 'id'
            )->get();
            return response()->json(['subServices' => $subServices]);
            // try {
            // } catch (\Throwable $th) {
            //     throw $th;
            // }
        }else{
            return dd('You are not authorize');
        }
    }

    public function NewSubService(Request $request)
    {
        try {
            if (Auth::check()) {
                Log::info($request);
                // $request->validate([
                //     'ServiceRequirements' => 'required|string|max:255|unique:services_sub_tables,ServiceRequirements',
                //     'ServicePrice' => 'required|min:0',
                // ]);

                $isSubServiceExists = ServicesSubTable::where('ServiceRequirements', $request['ServiceRequirements'])
                                ->where('BelongsToService', $request['serviceID'])
                                ->where('isVisible', true)
                                ->exists();

            if ($isSubServiceExists) {
                return response()->json(['error' => 'Sub-service already exists for this service'], 409);
            }
            $preparedPrice = floatval(preg_replace('/[^\d.]/', '', $request['ServicePrice']));
            Log::info($preparedPrice);
                ServicesSubTable::create([
                    'ServiceRequirements' => $request['ServiceRequirements'],
                    'ServiceRequirementPrice' => $preparedPrice,
                    'BelongsToService' => $request['serviceID'],
                    'dataEntryUser' => Auth::user()->id
                ]);
                return response()->json(['sub_service', 'created']);
            }else{
                return dd('You are not authorize');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function RetrieveSubService($id){
            if(Auth::check()){
                Log::info($id);
            try {
                $subservice = ServicesSubTable::where('id', $id)->first();
                return response()->json(['sub_service' => $subservice]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return dd('You are not authorize');
    }

    public function EditSubService(Request $request)
{
    try {
        if (Auth::check()) {
            Log::info($request);
            $request->validate([
                'service' => 'required|string|max:255|unique:services_sub_tables,ServiceRequirements,' . $request['sub-service-id'],
                'serviceprice' => 'required|min:0',
            ]);
            $preparedPrice = floatval($request['serviceprice']);
            ServicesSubTable::where('id', $request['sub-service-id'])
                ->update(['ServiceRequirements' => $request['service'], 'ServiceRequirementPrice' => $preparedPrice]);

            return response()->json(['sub_service' => 'updated']);
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    } catch (\Throwable $th) {
        return response()->json(['error' => 'An error occurred'], 500);
    }
}

}
// if(Auth::check()){
//     try {
//     } catch (\Throwable $th) {
//         throw $th;
//     }
// }
// return dd('You are not authorize');