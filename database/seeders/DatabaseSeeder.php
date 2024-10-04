<?php

namespace Database\Seeders;

use App\Models\ChartOfAccounts;
use App\Models\Services;
use App\Models\ServicesSubTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash as FacadesHash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        User::factory()->create([
            'FirstName' => 'Edrian',
            'LastName' => 'Lagrosa',
            'UserName' => 'edrian',
            'email' => 'edrian@gmail.com',
            'role' => 'Admin',
            'PIN' => '101106',
            'password' => FacadesHash::make('admin')
        ]);

        User::factory()->create([
            'FirstName' => 'Dave',
            'LastName' => 'Batista',
            'UserName' => 'Dave',
            'email' => 'Dave@gmail.com',
            'role' => 'Bookkeeper',
            'PIN' => '101105',
            'password' => FacadesHash::make('admin')
        ]);

        

        $services = [
            ['Service' => 'Application for Business Registration', 'Price' => 1000, 'dataEntryUser' => '1'],
            ['Service' => 'Business Permit Processing/Renewal', 'Price' => 1500, 'dataEntryUser' => '1'],
            ['Service' => 'Bookkeeping', 'Price' => 500, 'dataEntryUser' => '1'],
            ['Service' => 'Financial Statement', 'Price' => 2500, 'dataEntryUser' => '1'],
        ];

        $serviceIds = [];
        foreach ($services as $index => $service) {
            $createdService = Services::factory()->create($service);
            $serviceIds[$index + 1] = $createdService->id;
        }

        $servicesSubService = [
            ['BelongsToService' => 1, 'ServiceRequirements' => 'DTI', 'ServiceRequirementPrice' => 750, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'SECURITIES AND EXCHANGE COMMISSION', 'ServiceRequirementPrice' => 15000, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'SOCIAL SECURITY SYSTEM', 'ServiceRequirementPrice' => 500, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'PHILHEALTH', 'ServiceRequirementPrice' => 500, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'PAG-IBIG', 'ServiceRequirementPrice' => 500, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'BIR', 'ServiceRequirementPrice' => 1000, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => '1901', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => '0605', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 2, 'ServiceRequirements' => 'Previous BP', 'ServiceRequirementPrice' => 250, 'dataEntryUser' => '1'],
        ];

        foreach ($servicesSubService as $subService) {
            ServicesSubTable::factory()->create($subService);
        }
        $accountTypes = [
            ['AccountType' => 'Current Asset', 'Category' => 'Asset', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Fixed Assets', 'Category' => 'Asset', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Non-Current Assets', 'Category' => 'Asset', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Owner\'s Equity', 'Category' => 'Equity', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Stockholder\'s Equity', 'Category' => 'Equity', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Revenue', 'Category' => 'Equity', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Sales Revenue', 'Category' => 'Equity', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Less Direct Cost', 'Category' => 'Expenses', 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountType' => 'Operating Expenses', 'Category' => 'Expenses', 'isVisible' => true, 'dataUserEntry' => '1'],
        ];

        DB::table('account_types')->insert($accountTypes);

        $accounts = [
            // Current Assets
            ['AccountName' => 'Cash on Hand/Bank', 'AccountType' => 1, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Accounts Receivable', 'AccountType' => 1, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Inventory', 'AccountType' => 1, 'isVisible' => true, 'dataUserEntry' => '1'],
            
            // Non-Current Assets
            ['AccountName' => 'Property, Plants & Equipment', 'AccountType' => 3, 'isVisible' => true, 'dataUserEntry' => '1'],
            
            // Fixed Assets (same as Non-Current Assets in this case)
            ['AccountName' => 'Property, Plants & Equipment', 'AccountType' => 2, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Current Liabilities
            ['AccountName' => 'Accounts Payable', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Short-Term Loans', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Accrued Expenses', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Payroll Liabilities', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Taxes Payable', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Current Portion of Long-Term Debt', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Unearned Revenue', 'AccountType' => 4, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Long-Term Liabilities
            ['AccountName' => 'Long-Term Debt', 'AccountType' => 5, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Bonds Payable', 'AccountType' => 5, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Deferred Tax Liabilities', 'AccountType' => 5, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Lease Obligations', 'AccountType' => 5, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Pension Obligations', 'AccountType' => 5, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Owner's Equity
            ['AccountName' => 'Owner’s Capital', 'AccountType' => 6, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Owner’s Drawings', 'AccountType' => 6, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Stockholder's Equity
            ['AccountName' => 'Common Stock', 'AccountType' => 7, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Preferred Stock', 'AccountType' => 7, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Retained Earnings', 'AccountType' => 7, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Additional Paid-In Capital', 'AccountType' => 7, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Treasury Stock', 'AccountType' => 7, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Dividends', 'AccountType' => 7, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Operating Revenue
            ['AccountName' => 'Construction of Other Civil Engineering Projects', 'AccountType' => 8, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Sales Revenue
            ['AccountName' => 'Construction Supplies', 'AccountType' => 9, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Less Direct Cost
            ['AccountName' => 'Supplies & Materials', 'AccountType' => 10, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Labor & Overhead', 'AccountType' => 10, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Gasoline & Oil', 'AccountType' => 10, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Repairs & Maintenance', 'AccountType' => 10, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Depreciation Exp.', 'AccountType' => 10, 'isVisible' => true, 'dataUserEntry' => '1'],

            // Operating Expenses
            ['AccountName' => 'Amortization', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Meals and Snack', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Miscellaneous', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Taxes and Licenses', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Transportation Expense', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Communication, Light & Water', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
            ['AccountName' => 'Office Supplies', 'AccountType' => 11, 'isVisible' => true, 'dataUserEntry' => '1'],
        ];

        DB::table('accounts')->insert($accounts);
    }
}
