<?php

namespace Database\Seeders;

use App\Models\ChartOfAccounts;
use App\Models\Services;
use App\Models\ServicesSubTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash as FacadesHash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    protected $model = ChartOfAccounts::class;

    public function run(): void
    {
        // Creating Users
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

        // Seeding Chart of Accounts
        $chartOfAccounts = [
            ['Account' => 'Assets', 'AccountType' => 'Current Assets', 'Category' => 'Asset', 'AccountNames' => 'Cash on Hand/Bank'],
            ['Account' => 'Assets', 'AccountType' => 'Current Assets', 'Category' => 'Asset', 'AccountNames' => 'Accounts Receivable'],
            ['Account' => 'Assets', 'AccountType' => 'Current Assets', 'Category' => 'Asset', 'AccountNames' => 'Inventory'],
            ['Account' => 'Assets', 'AccountType' => 'Non-Current Assets', 'Category' => 'Asset', 'AccountNames' => 'Property, Plants & Equipment'],
            ['Account' => 'Assets', 'AccountType' => 'Fixed Assets', 'Category' => 'Asset', 'AccountNames' => 'Property, Plants & Equipment'],

            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Accounts Payable'],
            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Short-Term Loans'],
            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Accrued Expenses'],
            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Payroll Liabilities'],
            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Taxes Payable'],
            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Current Portion of Long-Term Debt'],
            ['Account' => 'Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Unearned Revenue'],

            ['Account' => 'Liabilities', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Long-Term Debt'],
            ['Account' => 'Liabilities', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Bonds Payable'],
            ['Account' => 'Liabilities', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Deferred Tax Liabilities'],
            ['Account' => 'Liabilities', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Lease Obligations'],
            ['Account' => 'Liabilities', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'AccountNames' => 'Pension Obligations'],

            ['Account' => 'Equity', 'AccountType' => 'Owner’s Equity', 'Category' => 'Equity', 'AccountNames' => 'Owner’s Capital'],
            ['Account' => 'Equity', 'AccountType' => 'Owner’s Equity', 'Category' => 'Equity', 'AccountNames' => 'Owner’s Drawings'],
            ['Account' => 'Equity', 'AccountType' => 'Stockholders\' Equity (Corporations)', 'Category' => 'Equity', 'AccountNames' => 'Common Stock'],
            ['Account' => 'Equity', 'AccountType' => 'Stockholders\' Equity (Corporations)', 'Category' => 'Equity', 'AccountNames' => 'Preferred Stock'],
            ['Account' => 'Equity', 'AccountType' => 'Stockholders\' Equity (Corporations)', 'Category' => 'Equity', 'AccountNames' => 'Retained Earnings'],

            ['Account' => 'Revenue', 'AccountType' => 'Operating Revenue', 'Category' => 'Revenue', 'AccountNames' => 'Construction of Other Civil Engineering Projects'],
            ['Account' => 'Revenue', 'AccountType' => 'Sales Revenue', 'Category' => 'Revenue', 'AccountNames' => 'Construction Supplies'],

            ['Account' => 'Expenses', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'AccountNames' => 'Supplies & Materials'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'AccountNames' => 'Labor & Overhead'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'AccountNames' => 'Gasoline & Oil'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'AccountNames' => 'Repairs & Maintenance'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'AccountNames' => 'Depreciation Exp.'],

            ['Account' => 'Expenses', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'AccountNames' => 'Amortization'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'AccountNames' => 'Meals and Snack'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'AccountNames' => 'Miscellaneous'],
            ['Account' => 'Expenses', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'AccountNames' => 'Taxes and Licenses'],
        ];

        foreach ($chartOfAccounts as $account) {
            ChartOfAccounts::factory()->create($account);
        }

        // Services and Sub-Services seeding
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
    }
}
