<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Mail\JournalBilling;
use App\Mail\MailClientJournalStatusToAccountant;
use App\Models\AccountDescription;
use App\Models\Accounts;
use App\Models\AccountType;
use App\Models\ActivityLog;
use App\Models\BillingAddedDescriptions;
use App\Models\BillingDescriptions;
use App\Models\Billings;
use App\Models\ClientJournal;
use App\Models\Clients;
use App\Models\ClientServices;
use App\Models\journal_expense_month;
use App\Models\JournalNote;
use App\Models\ServiceRequirement;
use App\Models\services;
use App\Models\ServicesDocuments;
use App\Models\ServicesSubTable;
use App\Models\SubServiceDocuments;
use App\Models\SubServiceRequirement;
use App\Models\SystemProfile;
use Date;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Mail;
use function Laravel\Prompts\select;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //temporary dd kay ngita pakog 403 template

    public function services()
    {
        if (auth::check()) {
            try {
                return view('pages.external-services');
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }
    public function addClientServices(Request $request)
    {
        if (auth::check()) {
            Log::info($request['id']);
            try {
                $services = Services::where('isVisible', true)->get();
                $client = Clients::where('id', $request['id'])->select('CompanyName', 'id')->first();
                foreach ($services as $service) {
                    $subServices = ServicesSubTable::where('isVisible', true)->where('id', $service->id)->get();
                }

                return view('forms.services-form', compact('services', 'client'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('You are not allowed');
    }

    public function adminHub()
    {
        if (auth::check()) {
            try {
                $logs = DB::table('activity_logs')
                    ->join('users', 'users.id', '=', 'activity_logs.user_id')
                    ->get();
                return view('pages.admin-hub', compact('logs'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }

    public function newClient()
    {
        if (auth::check()) {
            try {
                $services = services::where('isVisible', true)->get();
                return view('pages.client-form', compact('services'));
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        dd('fuck you are not allowed');
    }

    public function dashboard()
    {
        //Nostalgia is the soul’s longing for a past that time has irrevocably altered.
        if (auth::check()) {
            try {


                $salesBilling = 0;
                    $data = BillingDescriptions::select(DB::raw('SUM(amount) as total_amount'), DB::raw('MONTH(created_at) as month'))
                    ->groupBy(DB::raw('MONTH(created_at), YEAR(created_at)'))
                    ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                    ->where('has_reset', false)
                    ->get();

                $labels = [];
                $amounts = array_fill(0, 12, 0);
                Log::info($data);
                foreach ($data as $item) {
                    $month = $item->month - 1;
                    $amounts[$month] = (float) $item->total_amount;
                    $salesBilling += $item->total_amount;
                }
                $salesFi = $amounts;
                $totalSales = 0;
                $sales = ClientServices::select(
                    'client_services.id as ClientServiceId',
                    'client_services.ClientService as Service',
                    'services.Price as ServicePrice',
                    'services_sub_tables.ServiceRequirementPrice as SubServicePrice',
                    'client_services.created_at'
                )
                    ->leftJoin('services', 'services.Service', '=', 'client_services.ClientService')
                    ->leftJoin('services_sub_tables', 'services_sub_tables.ServiceRequirements', '=', 'client_services.ClientService')
                    ->whereYear('client_services.created_at', \Carbon\Carbon::now()->year)
                    ->get();

                $monthlySales = array_fill(0, 12, 0);

                foreach ($sales as $sale) {
                    $servicePrice = $sale->ServicePrice ?? 0;
                    $subServicePrice = $sale->SubServicePrice ?? 0;

                    $month = \Carbon\Carbon::parse($sale->created_at)->month - 1;

                    $monthlySales[$month] += $servicePrice + $subServicePrice;
                    $sales = $sale->SubServicePrice += $sale->ServicePrice;
                    $totalSales += $sales;
                }

                $clientPaymentStatus = Clients::where('clients.isVisible', true)
                    ->leftJoin('company_profiles', 'clients.id', '=', 'company_profiles.company')
                    ->join('client_services', 'client_services.Client', '=', 'clients.id')
                    ->select('clients.CompanyName', 'company_profiles.image_path', 'client_services.ClientService')
                    ->get();

                $client = Clients::where('isVisible', true)->get();
                $clientCount = count($client);

                $incomeBD = DB::table('billing_descriptions')
                    ->join('account_descriptions', 'billing_descriptions.description', '=', 'account_descriptions.id')
                    ->select(
                        'account_descriptions.Category',
                        'billing_descriptions.amount'
                    )
                    ->get();

                $incomeABD = DB::table('billing_added_descriptions')
                    ->join('account_descriptions', 'billing_added_descriptions.description', '=', 'account_descriptions.id')
                    ->select(
                        'account_descriptions.Category',
                        'billing_added_descriptions.amount',
                        'billing_added_descriptions.account'
                    )
                    ->get();
                $expenses = 0;
                $income = 0;
                $totalDB = 0;
                $totalADB = 0;
                Log::info($incomeABD);
                Log::info($incomeBD);
                foreach ($incomeABD as $value) {
                    if ($value->Category === 'Internal') {
                        $totalADB += $value->amount;
                    }
                }
                foreach ($incomeBD as $value) {
                    if ($value->Category === 'Internal') {
                        $totalDB += $value->amount;
                    }
                }

                $income = $totalDB + $totalADB;

                $clients = Clients::where('clients.accountCategory', true)
                    ->select(
                        'journal_expense_months.amount'
                    )
                    ->where('journal_expense_months.isAltered', false)
                    ->where('journal_expense_months.has_reset', false)
                    ->join('journal_expenses', 'journal_expenses.client_id', '=', 'clients.id')
                    ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
                    ->get();

                $expenses = 0;
                foreach ($clients as $value) {
                    $expenses += $value->amount;
                }
                $incomeData = DB::table('clients')
                ->where('clients.accountCategory', true)
                ->select(
                    DB::raw('MONTH(journal_income_months.created_at) as month'),
                    DB::raw('SUM(journal_income_months.amount) as total_income')
                )
                ->where('journal_income_months.isAltered', false)
                ->where('journal_income_months.has_reset', false)
                ->join('client_journals', 'client_journals.client_id', '=', 'clients.id')
                ->join('journal_incomes', 'journal_incomes.journal_id', '=', 'client_journals.journal_id')
                ->join('journal_income_months', 'journal_income_months.income_id', '=', 'journal_incomes.id')
                ->groupBy(DB::raw('MONTH(journal_income_months.created_at)'))
                ->get();

                $monthlyIncome = array_fill(0, 12, 0);
                $incomeInfo = 0;
                foreach ($incomeData as $income) {
                    $month = $income->month - 1;
                    $monthlyIncome[$month] += $income->total_income;
                    $incomeInfo += $income->total_income;
                }

                $expensesData = DB::table('clients')
                    ->where('clients.accountCategory', true)
                    ->select(
                        DB::raw('MONTH(journal_expense_months.created_at) as month'),
                        DB::raw('SUM(journal_expense_months.amount) as total_expense')
                    )
                    ->where('journal_expense_months.isAltered', false)
                    ->where('journal_expense_months.has_reset', false)
                    ->join('client_journals', 'client_journals.client_id', '=', 'clients.id')
                    ->join('journal_expenses', 'journal_expenses.journal_id', '=', 'client_journals.journal_id')
                    ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
                    ->groupBy(DB::raw('MONTH(journal_expense_months.created_at)'))
                    ->get();

                $monthlyExpenses = array_fill(0, 12, 0);

                foreach ($expensesData as $expense) {
                    $month = $expense->month - 1;
                    $monthlyExpenses[$month] += $expense->total_expense;
                }


                $activityLog = ActivityLog::whereDate('activity_logs.created_at', Carbon::today())
                    ->join('users', 'users.id', '=', 'activity_logs.user_id')
                    ->get();

                $clientsData = DB::table('clients')
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('COUNT(id) as total_clients')
                    )
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->get();

                $monthlyClients = array_fill(0, 12, 0);

                foreach ($clientsData as $client) {
                    $month = $client->month - 1;
                    $monthlyClients[$month] += $client->total_clients;
                }
                // Log::info($monthlySales);
                return view('pages.dashboard', compact('salesBilling','salesFi', 'expenses', 'incomeInfo', 'income', 'clientPaymentStatus', 'clientCount', 'monthlySales', 'totalSales', 'monthlyIncome', 'monthlyExpenses', 'activityLog', 'monthlyClients'));

            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                throw $exception;
            }
        }
        return redirect()->route('login');
    }





    public function ChartOfAccounts()
    {
        try {
            if (Auth::check()) {
                $at = AccountType::where('isVisible', true)->get();
                // $accounts = Accounts::where('isVisible', true)->get();
                $account = Accounts::where('accounts.isVisible', true)->
                    select('accounts.AccountName', 'accounts.id', 'account_types.AccountType', 'account_types.Category', 'account_types.id as ATid')
                    ->join('account_types', 'account_types.id', '=', 'accounts.AccountType')->get();
                return view('pages.chart-of-account', compact('at', 'account'));
            } else {
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function NewAccountType(Request $request)
    {
        try {
            if (Auth::check()) {
                DB::beginTransaction();
                // Validate the request
                $request->validate([
                    'AccountType' => 'required|string|unique:account_types,AccountType',
                    'Category' => 'required|string|in:Asset,Liability,Equity,Expenses,Revenue',
                ]);

                // Create a new account type
                AccountType::create([
                    'AccountType' => $request['AccountType'],
                    'Category' => $request['Category'],
                    'dataUserEntry' => Auth::user()->id,
                ]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'New Account Type Created',
                    'activity' => 'Created a new account type',
                    'description' => 'New account type' . $request['AccountType'] . '' . 'was created.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['success' => 'Data saved successfully']);
            } else {
                return response()->json(['error' => 'Unauthorized Access'], 403);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if ($e->validator->errors()->has('AccountType')) {
                return response()->json(['error' => 'Account Type already exists'], 409);
            }
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred: ' . $th->getMessage()], 500);
        }
    }


    // public function NewAccount(Request $request)
    // {
    //     try {
    //         if (Auth::check()) {
    //             $request->validate([
    //                 'AccountName' => 'required|string|unique:accounts,AccountName',
    //                 // 'Category' => 'required|string|in:Asset,Liability,Equity,Expenses',
    //             ]);

    //             Accounts::create([
    //                 'AccountName' => $request['AccountName'],
    //                 'AccountType' => $request['AccountType'],
    //                 'dataUserEntry' => Auth::user()->id,
    //             ]);
    //             ActivityLog::create([]);
    //             return response()->json(['success' => 'Data saved successfully']);
    //         } else {
    //             return response()->json(['error' => 'Unauthorized Access'], 403);
    //         }
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         if ($e->validator->errors()->has('AccountName')) {
    //             return response()->json(['error' => 'Account already exists'], 409);
    //         }
    //         return response()->json(['error' => $e->validator->errors()], 422);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => 'An error occurred: ' . $th->getMessage()], 500);
    //     }
    // }
    public function NewAccount(Request $request)
    {
        try {
            if (Auth::check()) {
                DB::beginTransaction();
                $request->validate([
                    'AccountName' => 'required|string|unique:accounts,AccountName',
                    'AccountType' => 'required|string',
                ]);

                Accounts::create([
                    'AccountName' => $request['AccountName'],
                    'AccountType' => $request['AccountType'],
                    'dataUserEntry' => Auth::user()->id,
                ]);

                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Account Created',
                    'activity' => 'Created a new account',
                    'description' => 'New account ' . $request['AccountName'] . '' . 'was created.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                Log::info('Client IP: ' . $userAgent);
                DB::commit();
                return response()->json(['success' => 'Data saved successfully']);
            } else {
                return response()->json(['error' => 'Unauthorized Access'], 403);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('AccountName')) {
                DB::rollBack();
                return response()->json(['error' => 'Account already exists'], 409);
            }
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred: ' . $th->getMessage()], 500);
        }
    }

    public function Users()
    {
        try {
            if (Auth::check()) {
                $users = User::where('isVisible', true)->get();
                return view('pages.users', compact('users'));
            } else {
                dd('unauthorized access');
            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function NewUser(Request $request)
    {
        try {
            DB::beginTransaction();
            if (Auth::check()) {
                Log::info($request);
                $request->validate([
                    'FirstName' => 'required|string|max:255',
                    'LastName' => 'required|string|max:255',
                    'UserName' => 'required|string|max:255|unique:users',
                    'Role' => 'required|string|max:50',
                    // 'PIN' => 'required|string|min:4|max:10|unique:users',
                    'password' => 'required|string|confirmed|min:8|unique:users',
                ]);
                User::create([
                    'FirstName' => $request['FirstName'],
                    'LastName' => $request['LastName'],
                    'UserName' => $request['UserName'],
                    'Email' => $request['Email'],
                    'Role' => $request['Role'],
                    // 'PIN' => $request['PIN'],
                    'password' => $request['password'],
                ]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'New User Created',
                    'activity' => 'Created a new user',
                    'description' => 'New user ' . $request['FirstName'] . ' - ' . $request['LastName'] . ' - ' . $request['Role'] . '' . 'was created.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['response' => 'user saved succesfully']);
            } else {
                dd('unauthorized access');
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function RemoveUser($id, Request $request)
    {
        try {
            DB::beginTransaction();
            if (Auth::check()) {
                $user = User::where('id', $id)->first();
                User::where('id', $id)->update(['isVisible' => 0]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'User Removed',
                    'activity' => 'User Removed',
                    'description' => 'User ' . $user->FirstName . ' - ' . $user->LastName . "- $user->Role" . '' . 'was removed.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['response' => 'User removed successfully']);
            } else {
                dd('Unauthorized Access');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function UpdateUser(Request $request)
    {
        if (Auth::check()) {
            Log::info($request);
            try {
                DB::beginTransaction();
                $request->validate([
                    'FirstName' => 'required|string|max:255',
                    'LastName' => 'required|string|max:255',
                    'UserName' => 'required|string|max:255|unique:users,UserName,' . $request->id,
                    'Email' => 'required|email|max:25|unique:users,Email,' . $request->id,
                    'Role' => 'required|string|max:50',
                    'PIN' => 'required|string|min:4|max:10|unique:users,PIN,' . $request->id,//must save for future reference
                ]);

                User::where('id', $request->id)->update([
                    'FirstName' => $request->FirstName,
                    'LastName' => $request->LastName,
                    'UserName' => $request->UserName,
                    'Email' => $request->Email,
                    'Role' => $request->Role,
                    'PIN' => $request->PIN,
                ]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'User Removed',
                    'activity' => 'User Removed',
                    'description' => "User {$request->FirstName} {$request->LastName} ({$request->Role}) was updated.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['response' => 'User Updated Successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            return response()->json(['error' => 'You are not authorized'], 403);
        }
    }

    public function Settings()
    {
        try {
            if (Auth::check()) {
                $salesBilling = 0;
                    $data = BillingDescriptions::select(DB::raw('SUM(amount) as total_amount'), DB::raw('MONTH(created_at) as month'))
                    ->groupBy(DB::raw('MONTH(created_at), YEAR(created_at)'))
                    ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                    ->where('has_reset', false)
                    ->get();

                $labels = [];
                $amounts = array_fill(0, 12, 0);
                Log::info($data);
                foreach ($data as $item) {
                    $month = $item->month - 1;
                    $amounts[$month] = (float) $item->total_amount;
                    $salesBilling += $item->total_amount;
                }
                $expenses = 0;
                $clients = Clients::where('clients.accountCategory', true)
                    ->select(
                        'journal_expense_months.amount'
                    )
                    ->where('journal_expense_months.isAltered', false)
                    ->where('journal_expense_months.has_reset', false)
                    ->join('journal_expenses', 'journal_expenses.client_id', '=', 'clients.id')
                    ->join('journal_expense_months', 'journal_expense_months.expense_id', '=', 'journal_expenses.id')
                    ->get();

                $expenses = 0;
                foreach ($clients as $value) {
                    $expenses += $value->amount;
                }

                $incomeBD = DB::table('billing_descriptions')
                    ->join('account_descriptions', 'billing_descriptions.description', '=', 'account_descriptions.id')
                    ->select(
                        'account_descriptions.Category',
                        'billing_descriptions.amount'
                    )
                    ->get();

                $incomeABD = DB::table('billing_added_descriptions')
                    ->join('account_descriptions', 'billing_added_descriptions.description', '=', 'account_descriptions.id')
                    ->select(
                        'account_descriptions.Category',
                        'billing_added_descriptions.amount',
                        'billing_added_descriptions.account'
                    )
                    ->get();
                $income = 0;
                $totalDB = 0;
                $totalADB = 0;
                foreach ($incomeABD as $value) {
                    if ($value->Category === 'Internal') {
                        $totalADB += $value->amount;
                    }
                }
                foreach ($incomeBD as $value) {
                    if ($value->Category === 'Internal') {
                        $totalDB += $value->amount;
                    }
                }

                $income = $totalDB + $totalADB;
                $incomeData = DB::table('clients')
                ->where('clients.accountCategory', true)
                ->select(
                    DB::raw('MONTH(journal_income_months.created_at) as month'),
                    DB::raw('SUM(journal_income_months.amount) as total_income')
                )
                ->where('journal_income_months.isAltered', false)
                ->where('journal_income_months.has_reset', false)
                ->join('client_journals', 'client_journals.client_id', '=', 'clients.id')
                ->join('journal_incomes', 'journal_incomes.journal_id', '=', 'client_journals.journal_id')
                ->join('journal_income_months', 'journal_income_months.income_id', '=', 'journal_incomes.id')
                ->groupBy(DB::raw('MONTH(journal_income_months.created_at)'))
                ->get();

                $monthlyIncome = array_fill(0, 12, 0);
                $incomeInfo = 0;
                foreach ($incomeData as $income) {
                    $month = $income->month - 1;
                    $monthlyIncome[$month] += $income->total_income;
                    $incomeInfo += $income->total_income;
                }


                $users = User::where('isVisible', true)->get();
                $sysProfile = SystemProfile::first();
                $accounts = Accounts::where('isVisible', true)->get();
                $services = services::where('isVisible', true)->get();
                $ad = AccountDescription::where('isVisible', true)->get();
                $adac = AccountDescription::where('account_descriptions.isVisible', true)
                    ->join('account_types', 'account_types.id', '=', 'account_descriptions.account')
                    ->join('services_sub_tables', 'services_sub_tables.id', '=', 'account_descriptions.account')
                    ->join('services', 'services.id', '=', 'services_sub_tables.BelongsToService')
                    ->join('accounts', 'accounts.id', '=', 'account_descriptions.account')
                    ->select(
                        'account_descriptions.Category',
                        'account_descriptions.Description',
                        'account_descriptions.TaxType',
                        'account_descriptions.FormType',
                        'account_descriptions.Price',
                        'account_descriptions.id',
                        'account_descriptions.Category as adCategory',
                        'services_sub_tables.ServiceRequirements',
                        'services_sub_tables.id as sub_service_id',
                        'services.Category',
                        'services.Service',
                        'services.id as service_id',
                        'accounts.AccountName'
                    )
                    ->get();
                return view('pages.settings', compact('users', 'sysProfile', 'accounts', 'services', 'ad', 'adac', 'expenses', 'incomeInfo', 'salesBilling'));
            } else {
                dd('Unauthorized Access');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function GetAccountTypes($id)
    {
        try {
            // Log::info($id);
            // return;
            if (Auth::check()) {
                $serviceTypes = ServicesSubTable::where('isVisible', true)->where('BelongsToService', $id)->get();
                return response()->json(['account' => $serviceTypes]);
            } else {

            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function NewAccountDescription(Request $request)
    {
        try {
            DB::beginTransaction();
            if (Auth::check()) {
                $request->validate([
                    'Description' => 'required|string|max:255|unique:account_descriptions,Description',
                    'TaxType' => 'required|string|max:255',
                    'FormType' => 'required|string|max:255|unique:account_descriptions,FormType',
                    'Price' => 'required|max:255',
                    'Category' => 'required|max:255',
                ]);
                Log::info($request['Price']);
                $sanitizePrice = str_replace(',', '', $request['Price']);
                $preparedPrice = floatval($sanitizePrice);
                Log::info($request['Type']);
                AccountDescription::create([
                    'Description' => $request['Description'],
                    'TaxType' => $request['TaxType'],
                    'FormType' => $request['FormType'],
                    'Price' => $preparedPrice,
                    'Category' => $request['Category'],
                    'account' => $request['Type'],
                    'dataUserEntry' => Auth::user()->id,
                ]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Account Description Created',
                    'activity' => 'Created a new account description',
                    'description' => "New account description $request[Description] $request[TaxType]($request[Category])",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['account_description', 'created']);
            } else {
                DB::rollBack();
                dd('unauthorized access');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function ReturnAccounts($id)
    {
        if (Auth::check()) {
            try {
                $preparedAccount = explode('_', $id);
                Log::info($id);
                // return;
                $assetsAT = Accounts::where('isVisible', true)->where('AccountType', $preparedAccount[1])->get();
                return response()->json(['assets' => $assetsAT]);
            } catch (\Exception $exception) {
                throw $exception;
            }
        } else {
            dd('unauthorized access');
        }
    }
    public function EditCOA(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $existingAccount = Accounts::where('AccountName', $request['AccountName'])
                    ->where('id', '!=', $request['id'])
                    ->first();

                if ($existingAccount) {
                    return response()->json(['message' => 'Account name already exists.'], 400);
                }
                Accounts::where('id', $request['id'])->update([
                    'AccountName' => $request['AccountName'],
                    'AccountType' => $request['AccountType']
                ]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Account Updated',
                    'activity' => 'updated an account',
                    'description' => "Account '{$request['AccountName']}' was updated.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['account' => 'Account updated successfully'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }

    public function AccountantInterface()
    {
        if (Auth::check()) {
            try {
                //             $journals = ClientJournal::where('client_journals.isVisible', true)
                // ->select(
                //     'clients.CEO', 
                //     'clients.CompanyName', 
                //     'clients.id as client_id',
                //     'client_journals.journal_id', 
                //     'client_journals.JournalStatus', 
                //     'client_journals.dataUserEntry',
                //     'users_entry.FirstName as EntryFirstName', 
                //     'users_entry.LastName as EntryLastName', 
                //     'users_entry.Role as EntryRole', 
                //     'client_journals.id', 
                //     'journal_notes.note', 
                //     'users_note.FirstName as NoteFirstName', 
                //     'users_note.LastName as NoteLastName', 
                //     'users_note.Role as NoteRole'
                // )
                // ->leftJoin('journal_notes', 'journal_notes.journal_id', '=', 'client_journals.id')
                // ->leftJoin('users as users_note', 'users_note.id', '=', 'journal_notes.user') // Alias for users in journal_notes
                // ->join('clients', 'clients.id', '=', 'client_journals.client_id')
                // ->join('users as users_entry', 'users_entry.id', '=', 'client_journals.dataUserEntry') // Alias for users in dataUserEntry
                // ->get();
                $accountants = User::where('isVisible', true)->where('Role', '!=', 'Bookkeeper')->get();
                $journals = ClientJournal::where('client_journals.isVisible', true)
                    ->select(
                        'clients.CEO',
                        'clients.CompanyName',
                        'clients.id as client_id',
                        'client_journals.journal_id',
                        'client_journals.JournalStatus',
                        'client_journals.dataUserEntry',
                        'users.FirstName',
                        'users.LastName',
                        'users.Role',
                        'client_journals.id',
                        'journal_notes.note',
                        'journal_notes.created_at as note_created_at', // Include created_at of the note
                        'users_note.FirstName as accountantFname',
                        'users_note.LastName as accountantLname',
                        'users_note.Role as accountantRole',
                        'users_note.created_at as NoteTimeStamp'
                    )
                    ->leftJoin('journal_notes', 'journal_notes.journal_id', '=', 'client_journals.id')
                    ->leftJoin('users as users_note', 'users_note.id', '=', 'journal_notes.user')
                    ->join('clients', 'clients.id', '=', 'client_journals.client_id')
                    ->join('users', 'users.id', '=', 'client_journals.dataUserEntry')
                    ->get()
                    ->groupBy('journal_id')
                    ->map(function ($items) {
                        $firstItem = $items->first();

                        $notes = $items->map(function ($item) {
                            return [
                                'note' => $item->note,
                                'created_at' => $item->note_created_at,
                                'role' => $item->accountantRole,
                                'userFname' => $item->accountantFname,
                                'userrLname' => $item->accountantLname,
                            ];
                        })->toArray();

                        $firstItem->notes = $notes;
                        return $firstItem;
                    })
                    ->values();
                    Log::info($journals);

                    
                Log::info(json_encode($journals, JSON_PRETTY_PRINT));

                return view('pages.journals', compact('journals', 'accountants'));

            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }
    public function UpdateJournalStatus(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                ClientJournal::where('journal_id', $request['journal_id'])->update(['JournalStatus' => $request['JournalStatus']]);
                JournalNote::create([
                    'journal_id' => $request['journalID'],
                    'note' => $request['journal-draft-note'],
                    'user' => Auth::user()->id
                ]);
                $journalID = ClientJournal::where('id', $request['journalID'])->value('journal_id');

                $client = ClientJournal::where('client_journals.journal_id', $request['journal_id'])
                    ->join('clients', 'clients.id', '=', 'client_journals.client_id')
                    ->first();
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Client Journal Status Updated',
                    'activity' => 'Client Journal Status Updated',
                    'description' => "Journal status updated Journal ID $request[journal_id].",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                // Mail::to($client->CompanyEmail)->send(new JournalBilling($request['JournalStatus']));
                Mail::to($request['Accountants'])->send(new MailClientJournalStatusToAccountant($request['JournalStatus'], $client->CompanyName, $request['Accountants'], $journalID));
                DB::commit();
                return response()->json(['journal-status', 'updated']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }

    public function ArchiveJournalEntry($id)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $preparedID = explode('-', $id);
                ClientJournal::where('journal_id', $preparedID[2])->update(['isVisible' => false]);
                $client = ClientJournal::where('client_journals.journal_id', $preparedID[2])
                    ->join('clients', 'clients.id', '=', 'client_journals.client_id')
                    ->select('clients.CEO', 'clients.CompanyName', 'client_journals.journal_id')
                    ->get();
                
                    Log::info($client);
                // $userAgent = $request->header('User-Agent');
                // $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                // ActivityLog::create([
                //     'user_id' => Auth::user()->id,
                //     'action' => 'Client Journal Status Updated',
                //     'activity' => 'Client Journal Status Updated',
                //     'description' => "Journal status updated for Client: {$client['CEO']}, Company: {$client['CompanyName']}, Journal ID: {$request['journal_id']}.",
                //     'ip_address' => $request->ip(),
                //     'user_agent' => $userAgent,
                //     'browser' => $browserDetails['browser'] ?? null,
                //     'platform' => $browserDetails['platform'] ?? null,
                //     'platform_version' => $browserDetails['platform_version'] ?? null,
                // ]);
                DB::commit();
                return response()->json(['Journal Entry', 'Move to Archive']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }

    public function EditSystemProfile(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                SystemProfile::where('id', 1)->update([
                    'PhoneNumber' => $request['PhoneNumber'],
                    'Email' => $request['Email'],
                    'Address' => $request['Address']
                ]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'System Profile Info Updated',
                    'activity' => 'System Profile Info Updated',
                    'description' => "System Profile was updated",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['system-profile' => 'updated']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }
    public function toggleUserLogInPrivilege($id, Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $user = User::findOrFail($id);
                $user->UserPrivilege = $user->UserPrivilege == 1 ? 0 : 1;
                $user->save();
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'User Role Updated',
                    'activity' => 'User Role Updated',
                    'description' => "User login privilege for {$user->FirstName} {$user->LastName} (ID: {$user->id}) was " . ($user->UserPrivilege == 1 ? 'enabled' : 'disabled') . ".",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => $user->UserPrivilege == 1
                        ? 'User login enabled successfully.'
                        : 'User login disabled successfully.'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized accecss');
        }
    }

    public function RemoveSubService($id, Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                ServicesSubTable::where('id', $id)->update(['isVisible' => false]);
                $sst = ServicesSubTable::where('id', $id)->first();
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Sub-Service Removed',
                    'activity' => 'Sub-Service Removal',
                    'description' => "Sub-service {$sst->ServiceRequirements} was removed.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);
                DB::commit();
                return response()->json(['sub-service', 'removed']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }

    public function UpdateDescription(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $preparedPrice = (float) str_replace(',', '', $request['price']);
                AccountDescription::where('id', $request['ad_id'])->update([
                    'Description' => $request['description'],
                    'TaxType' => $request['taxType'],
                    'FormType' => $request['formType'],
                    'Price' => $preparedPrice,
                    'Category' => $request['category'],
                    'account' => $request['Type'],
                    'dataUserEntry' => Auth::user()->id,
                ]);

                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Account Description Updated',
                    'activity' => 'Updated Account Description',
                    'description' => "Account description was updated. Description: '{$request['description']}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);

                DB::commit();
                return response()->json(['account_description' => 'updated']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }


    public function RemoveDescription($id, Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $accountDescription = AccountDescription::findOrFail($id);
                $accountDescription->update(['isVisible' => false]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Account Description Removed',
                    'activity' => 'Removed Account Description',
                    'description' => "Account description '{$accountDescription->Description}' was removed",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);

                DB::commit();
                return response()->json(['description' => 'removed']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }


    public function RemoveCOA($id, Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                $account = Accounts::findOrFail($id);
                $account->update(['isVisible' => false]);
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Chart of Accounts Updated',
                    'activity' => 'Account Visibility Updated',
                    'description' => "The account '{$account->AccountName}' was removed.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);

                DB::commit();
                return response()->json(['description' => 'removed']);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('unauthorized access');
        }
    }



    public function AddServiceReq(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();

                // Log the incoming request data (for debugging purposes)
                Log::info("Request $request");

                // Iterate over the form data and create service requirements
                foreach ($request['form'] as $value) {
                    ServiceRequirement::create([
                        'service_id' => $request['idref'],
                        'req_name' => $value['value']
                    ]);
                }

                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Service Requirement Added',
                    'activity' => 'New service requirements were added',
                    'description' => "New requirements were added.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);

                DB::commit();
                return response()->json(['service_name' => 'added'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            // Unauthorized access handling
            dd('Unauthorized access');
        }
    }

    public function requirementForService(Request $request){
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                // Log the incoming request data (for debugging purposes)
                Log::info("Request $request");

                // Iterate over the form data and create service requirements
                foreach ($request['form'] as $value) {
                    ServiceRequirement::create([
                        'service_id' => $request['idref'],
                        'req_name' => $value['value']
                    ]);
                }

                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);
                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Service Requirement Added',
                    'activity' => 'New service requirements were added',
                    'description' => "New requirements were added.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);

                DB::commit();
                return response()->json(['service_name' => 'added'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        } else {
            // Unauthorized access handling
            dd('Unauthorized access');
        }
    }


    public function AddSubServiceReq(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                Log::info($request['reqName']);
                SubServiceRequirement::create([
                    'req_name' => $request['reqName'],
                    'sub_service_id' => $request['idRef']
                ]);
                $subServiceName = ServicesSubTable::find($request['idRef'])->name ?? 'Unknown Sub-Service';
                $userAgent = $request->header('User-Agent');
                $browserDetails = CustomHelper::getBrowserDetails($userAgent);

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'action' => 'Sub-Service Requirement Added',
                    'activity' => 'New sub-service requirements were added',
                    'description' => "New requirements were added.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'browser' => $browserDetails['browser'] ?? null,
                    'platform' => $browserDetails['platform'] ?? null,
                    'platform_version' => $browserDetails['platform_version'] ?? null,
                ]);

                DB::commit();
                return response()->json(['service_name' => 'added'], 200);
            } catch (\Throwable $th) {
                Log::info($th);
                DB::rollBack();
                throw $th;
            }
        } else {
            dd('Unauthorized access');
        }
    }

    public function GetServiceReq($id)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access');
        }

        try {
            // Split the identifier into parts
            $prepID = explode('_', $id);

            // Ensure the ID has sufficient parts to process
            if (count($prepID) < 4) {
                return response()->json(['error' => 'Invalid ID format'], 400);
            }

            // Extract the relevant parts
            $type = $prepID[1]; // 'service' or 'subservice'
            $serviceId = $prepID[2];
            $clientId = $prepID[3];

            if ($type === 'subservice') {
                // Get IDs of sub-service requirements that the client has already uploaded
                $existingDocs = SubServiceDocuments::where('client_id', $clientId)
                    ->where('service_id', $serviceId) // This links to the sub-service requirement
                    ->pluck('service_id'); // This corresponds to sub_service_requirements.id

                // Get sub-service requirements excluding those already fulfilled
                $serviceDocs = SubServiceRequirement::where('sub_service_id', $serviceId)
                    ->whereNotIn('id', $existingDocs)
                    ->get();
            } elseif ($type === 'service') {
                // Get IDs of service requirements that the client has already uploaded
                $existingDocs = ServicesDocuments::where('client_id', $clientId)
                    ->where('service_id', $serviceId) // This links to the service requirement
                    ->pluck('service_id'); // This corresponds to service_requirements.id

                // Get service requirements excluding those already fulfilled
                $serviceDocs = ServiceRequirement::where('service_id', $serviceId)
                    ->whereNotIn('id', $existingDocs)
                    ->get();
                Log::info(ServiceRequirement::where('service_id', $serviceId)->whereNotIn('id', $existingDocs)->toSql());

            } else {
                return response()->json(['error' => 'Invalid service type'], 400);
            }

            // Check if the serviceDocs are being correctly filtered
            Log::info('Existing Docs:', $existingDocs->toArray());
            Log::info('Filtered Service Requirements:', $serviceDocs->toArray());

            return response()->json(['serviceReqs' => $serviceDocs]);

        } catch (\Throwable $th) {
            Log::error("Error in GetServiceReq: " . $th->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }





    public function NewServiceDocument(Request $request)
    {
        if (Auth::check()) {
            try {
                DB::beginTransaction();
                foreach ($request->file('files', []) as $key => $file) {
                    $prepKey = explode('_', $key);
                    $subServiceID = $prepKey[0];//service requirement id
                    $subServiceReqID = $prepKey[1];//client service id
                    $clientID = $prepKey[3]; //client id
                    $category = $prepKey[2];//category
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();
                    $profilePath = $file->storeAs('client-files', $fileName, 'public');
                    $data = [
                        'service_id' => $subServiceID,
                        'client_id' => $clientID,
                        'client_service' => $subServiceReqID,
                        'ReqName' => $file->getClientOriginalName(),
                        'getClientOriginalName' => $file->getClientOriginalName(),
                        'getClientMimeType' => $file->getMimeType(),
                        'getSize' => $file->getSize(),
                        'getRealPath' => $profilePath,
                        'dataEntryUser' => Auth::user()->id,
                        'isVisible' => true,
                    ];
                    if ($category === 'service') {
                        ServicesDocuments::create($data);
                    } else {
                        SubServiceDocuments::create($data);
                    }
                    $service = ClientServices::where('id', $subServiceReqID)->first();
                    ActivityLog::create([
                        'user_id' => Auth::user()->id,
                        'action' => 'Service Document Uploaded',
                        'activity' => 'Service Document Uploaded',
                        'description' => "File '{$fileName}' uploaded for the service '{$service}'",
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('User-Agent'),
                        'browser' => CustomHelper::getBrowserDetails($request->header('User-Agent'))['browser'] ?? null,
                        'platform' => CustomHelper::getBrowserDetails($request->header('User-Agent'))['platform'] ?? null,
                        'platform_version' => CustomHelper::getBrowserDetails($request->header('User-Agent'))['platform_version'] ?? null,
                    ]);
                }
                DB::commit();
                return response()->json(['message' => 'Files uploaded successfully.']);

            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error('Error uploading files: ' . $th->getMessage());
                return response()->json(['message' => 'An error occurred while uploading files.'], 500);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }


    public function Income()
    {
        if (Auth::check()) {
            try {
                $incomeData = DB::table('client_journals')
                    ->join('journal_incomes', 'client_journals.journal_id', '=', 'journal_incomes.journal_id')
                    ->join('journal_income_months', 'journal_incomes.id', '=', 'journal_income_months.income_id')
                    ->select(
                        'client_journals.journal_id',
                        'client_journals.created_at',
                        DB::raw('SUM(journal_income_months.amount) as total_amount')
                    )
                    ->groupBy('client_journals.journal_id', 'client_journals.created_at')
                    ->get();
                Log::info(json_encode($incomeData, JSON_PRETTY_PRINT));
                return view('pages.income', compact('incomeData'));
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            abort(403, 'unauthorized access');
        }
    }


    public function Expenses()
    {
        if (Auth::check()) {
            try {
                $expenses = DB::table('client_journals')
                    ->join('journal_expenses', 'client_journals.journal_id', '=', 'journal_expenses.journal_id')
                    ->join('journal_expense_months', 'journal_expenses.id', '=', 'journal_expense_months.expense_id')
                    ->select(
                        'client_journals.journal_id',
                        'client_journals.created_at',
                        DB::raw('SUM(journal_expense_months.amount) as total_amount')
                    )
                    ->groupBy('client_journals.journal_id', 'client_journals.created_at')
                    ->get();

                Log::info(json_encode($expenses, JSON_PRETTY_PRINT));

                return view('pages.expenses', compact('expenses'));
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            abort(403, 'unauthorized access');
        }
    }

    public function QuarterlyExpense($quarter)
{
    if (Auth::check()) {
        try {
            $currentYear = date('Y');
            $startDate = '';
            $endDate = '';
            $monthsInQuarter = [];

            switch ($quarter) {
                case 'Q1':
                    $startDate = "$currentYear-01-01";
                    $endDate = "$currentYear-03-31";
                    $monthsInQuarter = ['Jan', 'Feb', 'Mar'];
                    break;
                case 'Q2':
                    $startDate = "$currentYear-04-01";
                    $endDate = "$currentYear-06-30";
                    $monthsInQuarter = ['Apr', 'May', 'Jun'];
                    break;
                case 'Q3':
                    $startDate = "$currentYear-07-01";
                    $endDate = "$currentYear-09-30";
                    $monthsInQuarter = ['Jul', 'Aug', 'Sep'];
                    break;
                case 'Q4':
                    $startDate = "$currentYear-10-01";
                    $endDate = "$currentYear-12-31";
                    $monthsInQuarter = ['Oct', 'Nov', 'Dec'];
                    break;
                default:
                    abort(400, 'Invalid quarter');
            }

            $quarterResult = [
                'total' => 0,
                'details' => []
            ];

            $expenses = DB::table('journal_expense_months')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $expensesByMonth = $expenses->groupBy(function ($expense) {
                return \Carbon\Carbon::parse($expense->created_at)->format('M');
            });

            foreach ($monthsInQuarter as $month) {
                $monthlyTotal = 0;

                if (isset($expensesByMonth[$month])) {
                    foreach ($expensesByMonth[$month] as $expense) {
                        $monthlyTotal += $expense->amount;
                    }
                }

                $quarterResult['total'] += $monthlyTotal;
                $quarterResult['details'][$month] = [
                    'total' => $monthlyTotal
                ];
            }

            return response()->json([
                $quarter => $quarterResult
            ]);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    } else {
        abort(403, 'Unauthorized access');
    }
}



public function QuarterlyIncome($quarter)
{
    if (Auth::check()) {
        try {
            $currentYear = date('Y');
            $startDate = '';
            $endDate = '';
            $monthsInQuarter = [];

            switch ($quarter) {
                case 'Q1':
                    $startDate = "$currentYear-01-01";
                    $endDate = "$currentYear-03-31";
                    $monthsInQuarter = ['Jan', 'Feb', 'Mar'];
                    break;
                case 'Q2':
                    $startDate = "$currentYear-04-01";
                    $endDate = "$currentYear-06-30";
                    $monthsInQuarter = ['Apr', 'May', 'Jun'];
                    break;
                case 'Q3':
                    $startDate = "$currentYear-07-01";
                    $endDate = "$currentYear-09-30";
                    $monthsInQuarter = ['Jul', 'Aug', 'Sep'];
                    break;
                case 'Q4':
                    $startDate = "$currentYear-10-01";
                    $endDate = "$currentYear-12-31";
                    $monthsInQuarter = ['Oct', 'Nov', 'Dec'];
                    break;
                default:
                    abort(400, 'Invalid quarter');
            }

            $quarterResult = [
                'total' => 0,
                'details' => []
            ];

            $expenses = DB::table('journal_income_months')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $expensesByMonth = $expenses->groupBy(function ($expense) {
                return \Carbon\Carbon::parse($expense->created_at)->format('M');
            });

            foreach ($monthsInQuarter as $month) {
                $monthlyTotal = 0;

                if (isset($expensesByMonth[$month])) {
                    foreach ($expensesByMonth[$month] as $expense) {
                        $monthlyTotal += $expense->amount;
                    }
                }

                $quarterResult['total'] += $monthlyTotal;
                $quarterResult['details'][$month] = [
                    'total' => $monthlyTotal
                ];
            }

            return response()->json([
                $quarter => $quarterResult
            ]);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    } else {
        abort(403, 'Unauthorized access');
    }
}

public function filterByYearIncome(Request $request)
    {
        try {
            $year = $request->input('year', date('Y')); // Default to current year if not provided
    
            $expenses = DB::table('journal_income_months')
                ->whereYear('created_at', $year)
                ->get();
    
            $yearResult = $expenses->groupBy(function ($expense) {
                return \Carbon\Carbon::parse($expense->created_at)->format('M');
            });
    
            $result = [];
            foreach ($yearResult as $month => $expenses) {
                $monthlyTotal = 0;
                foreach ($expenses as $expense) {
                    $monthlyTotal += $expense->amount;
                }
                $result[$month] = $monthlyTotal;
            }
    
            return response()->json($result);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function filterByYearExpense(Request $request)
    {
        try {
            $year = $request->input('year', date('Y')); // Default to current year if not provided
    
            $expenses = DB::table('journal_expense_months')
                ->whereYear('created_at', $year)
                ->get();
    
            $yearResult = $expenses->groupBy(function ($expense) {
                return \Carbon\Carbon::parse($expense->created_at)->format('M');
            });
    
            $result = [];
            foreach ($yearResult as $month => $expenses) {
                $monthlyTotal = 0;
                foreach ($expenses as $expense) {
                    $monthlyTotal += $expense->amount;
                }
                $result[$month] = $monthlyTotal;
            }
    
            return response()->json($result);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


public function QuarterlyClient($quarter)
{
    Log::info("Fetching data for quarter: " . $quarter);

    $year = now()->year;

    $quarters = [
        'Q1' => ['start' => '01-01', 'end' => '03-31', 'months' => ['Jan', 'Feb', 'Mar']],
        'Q2' => ['start' => '04-01', 'end' => '06-30', 'months' => ['Apr', 'May', 'Jun']],
        'Q3' => ['start' => '07-01', 'end' => '09-30', 'months' => ['Jul', 'Aug', 'Sep']],
        'Q4' => ['start' => '10-01', 'end' => '12-31', 'months' => ['Oct', 'Nov', 'Dec']]
    ];

    if (!isset($quarters[$quarter])) {
        return response()->json(['error' => 'Invalid quarter selected'], 400);
    }

    $startDate = "{$year}-{$quarters[$quarter]['start']}";
    $endDate = "{$year}-{$quarters[$quarter]['end']}";

    $data = DB::table('clients')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month', 'asc')
        ->get();

    $quarterData = [];

    // Initialize the quarter data with zero values for each month in the selected quarter
    foreach ($quarters[$quarter]['months'] as $month) {
        $quarterData[$month] = ['total' => 0];
    }

    // Fill the data array with the actual data from the database
    foreach ($data as $row) {
        $monthName = $quarters[$quarter]['months'][$row->month - 1];
        $quarterData[$monthName] = ['total' => $row->total];
    }

    return response()->json([
        $quarter => [
            'months' => $quarters[$quarter]['months'],
            'details' => $quarterData
        ]
    ]);
}

public function filterByYearClient(Request $request)
{
    try {
        $year = $request->input('year', date('Y')); // Default to the current year if not provided

        // Fetch and group clients by month for the selected year
        $clients = DB::table('clients')
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($client) {
                return \Carbon\Carbon::parse($client->created_at)->format('M'); // Group by month (e.g., Jan, Feb)
            });

        // Prepare result: count the number of clients for each month
        $result = [];
        foreach ($clients as $month => $group) {
            $result[$month] = $group->count(); // Count the number of clients in each month
        }

        return response()->json($result);
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}


public function QuarterlyBilling($quarter)
{
    try {
        Log::info("Fetching billing data for quarter: " . $quarter);

        $year = now()->year;

        // Define the start and end dates for each quarter
        $quarters = [
            'Q1' => ['start' => '01-01', 'end' => '03-31'],
            'Q2' => ['start' => '04-01', 'end' => '06-30'],
            'Q3' => ['start' => '07-01', 'end' => '09-30'],
            'Q4' => ['start' => '10-01', 'end' => '12-31']
        ];

        // Validate if the selected quarter exists
        if (!isset($quarters[$quarter])) {
            return response()->json(['error' => 'Invalid quarter selected'], 400);
        }

        // Calculate the start and end date for the selected quarter
        $startDate = Carbon::parse("{$year}-{$quarters[$quarter]['start']}")->startOfDay();
        $endDate = Carbon::parse("{$year}-{$quarters[$quarter]['end']}")->endOfDay();

        Log::info("Start Date: " . $startDate->toDateTimeString());
        Log::info("End Date: " . $endDate->toDateTimeString());

        // Query data from services and sub-services
        $sales = DB::table('client_services')
            ->select(
                DB::raw('MONTH(client_services.created_at) as month'),
                DB::raw('SUM(services.Price) as total_service_price'),
                DB::raw('SUM(services_sub_tables.ServiceRequirementPrice) as total_sub_service_price')
            )
            ->leftJoin('services', 'services.Service', '=', 'client_services.ClientService')
            ->leftJoin('services_sub_tables', 'services_sub_tables.ServiceRequirements', '=', 'client_services.ClientService')
            ->whereBetween('client_services.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('MONTH(client_services.created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        Log::info("Sales Data from DB: " . json_encode($sales));

        // Define the month names for the selected quarter
        $monthNames = [
            'Q1' => ['Jan', 'Feb', 'Mar'],
            'Q2' => ['Apr', 'May', 'Jun'],
            'Q3' => ['Jul', 'Aug', 'Sep'],
            'Q4' => ['Oct', 'Nov', 'Dec']
        ];

        // Initialize the months with default values (0) for the quarter
        $quarterData = [];
        foreach ($monthNames[$quarter] as $month) {
            $quarterData[$month] = ['total' => 0];
        }

        // Populate the quarter data with actual values from the query
        $totalBilling = 0;
        foreach ($sales as $sale) {
            $monthIndex = $sale->month - 1; // Convert month number to zero-based index
            $monthName = $monthNames[$quarter][$monthIndex % 3]; // Map to quarter-specific month names

            $totalForMonth = (float) ($sale->total_service_price + $sale->total_sub_service_price);
            Log::info("Mapped Month: " . $monthName . ", Total Billing: " . $totalForMonth);

            $quarterData[$monthName]['total'] = $totalForMonth;
            $totalBilling += $totalForMonth;
        }

        Log::info("Final Quarter Data: " . json_encode($quarterData));

        return response()->json([
            $quarter => [
                'total' => $totalBilling,
                'details' => $quarterData
            ]
        ]);
    } catch (\Throwable $th) {
        Log::error("Error fetching quarterly billing data: " . $th->getMessage());
        return response()->json(['error' => $th->getMessage()], 500);
    }
}

public function YearlyBilling(Request $request)
{
    try {
        $year = $request->input('year', now()->year); // Default to the current year if not provided

        Log::info("Fetching yearly sales data for year: " . $year);

        // Retrieve sales data for the specified year
        $sales = ClientServices::select(
            'client_services.id as ClientServiceId',
            'client_services.ClientService as Service',
            'services.Price as ServicePrice',
            'services_sub_tables.ServiceRequirementPrice as SubServicePrice',
            'client_services.created_at'
        )
            ->leftJoin('services', 'services.Service', '=', 'client_services.ClientService')
            ->leftJoin('services_sub_tables', 'services_sub_tables.ServiceRequirements', '=', 'client_services.ClientService')
            ->whereYear('client_services.created_at', $year)
            ->get();

        // Initialize an array to hold monthly totals
        $monthlySales = array_fill(0, 12, 0);
        $totalSales = 0; // Track total yearly sales

        // Process each sale and calculate monthly totals
        foreach ($sales as $sale) {
            $servicePrice = $sale->ServicePrice ?? 0;       // Default to 0 if null
            $subServicePrice = $sale->SubServicePrice ?? 0; // Default to 0 if null

            $month = \Carbon\Carbon::parse($sale->created_at)->month - 1; // Convert month to zero-based index

            $monthlySales[$month] += $servicePrice + $subServicePrice;

            // Calculate total sales for all months
            $totalSales += $servicePrice + $subServicePrice;
        }

        // Prepare month names for output
        $monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        // Transform monthly sales into a more readable format
        $yearData = [];
        foreach ($monthlySales as $index => $monthlyTotal) {
            $yearData[$monthNames[$index]] = ['total' => $monthlyTotal];
        }

        Log::info("Yearly sales data for year $year: " . json_encode($yearData));

        return response()->json([
            'year' => $year,
            'total_sales' => $totalSales,
            'details' => $yearData,
        ]);
    } catch (\Throwable $th) {
        Log::error("Error fetching yearly sales data: " . $th->getMessage());
        return response()->json(['error' => $th->getMessage()], 500);
    }
}



    public function BillingLists(){
        if(Auth::check()){
            try {
                $billing = DB::table('billings')
                ->join('clients', 'clients.id', '=', 'billings.client_id')
                ->select('billings.billing_id', 'clients.id', 'clients.CEO', 'clients.CompanyName', 'billings.created_at')
                ->get();
                return view('pages.billing-lists', compact('billing'));
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            abort(403, 'unauthorized access');
        }
    }
    public function ResetExpense() {
        if (Auth::check()) {
            try {
                DB::table('journal_expense_months')->update(['has_reset' => true]);
                return response()->json(['message' => 'Expenses reset successfully']);
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            abort(403, 'Unauthorized access');
        }
    }

    public function ResetIncome() {
        if (Auth::check()) {
            try {
                DB::table('journal_income_months')->update(['has_reset' => true]);
                return response()->json(['message' => 'Income reset successfully']);
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            abort(403, 'Unauthorized access');
        }
    }
    
    public function ResetSales() {
        if (Auth::check()) {
            try {
                DB::table('billing_descriptions')->update(['has_reset' => true]);
                return response()->json(['message' => 'Income reset successfully']);
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            abort(403, 'Unauthorized access');
        }
    }
}

// try {
//     if(Auth::check()){

//     }else{

//     }
// } catch (\Throwable $th) {
//     throw $th;
// }