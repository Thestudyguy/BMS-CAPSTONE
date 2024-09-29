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

        $chartOfAccounts = [
            ['Account' => 'Cash on Hand/Bank', 'AccountType' => 'Current Assets', 'Category' => 'Asset', 'dataUserEntry' => 1],
            ['Account' => 'Accounts Receivable', 'AccountType' => 'Current Assets', 'Category' => 'Asset', 'dataUserEntry' => 1],
            ['Account' => 'Inventory', 'AccountType' => 'Current Assets', 'Category' => 'Asset', 'dataUserEntry' => 1],
            ['Account' => 'Property, Plants & Equipment', 'AccountType' => 'Non-Current Assets', 'Category' => 'Asset', 'dataUserEntry' => 1],
            ['Account' => 'Property, Plants & Equipment', 'AccountType' => 'Fixed Assets', 'Category' => 'Asset', 'dataUserEntry' => 1],
            
            ['Account' => 'Accounts Payable', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Short-Term Loans', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Accrued Expenses', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Payroll Liabilities', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Taxes Payable', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Current Portion of Long-Term Debt', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Unearned Revenue', 'AccountType' => 'Current Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            
            ['Account' => 'Long-Term Debt', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Bonds Payable', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Deferred Tax Liabilities', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Lease Obligations', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            ['Account' => 'Pension Obligations', 'AccountType' => 'Long-Term Liabilities', 'Category' => 'Liability', 'dataUserEntry' => 1],
            
            ['Account' => 'Owner’s Capital', 'AccountType' => 'Owner’s Equity', 'Category' => 'Equity', 'dataUserEntry' => 1],
            ['Account' => 'Owner’s Drawings', 'AccountType' => 'Owner’s Equity', 'Category' => 'Equity', 'dataUserEntry' => 1],
            ['Account' => 'Common Stock', 'AccountType' => 'Stockholders\' Equity (Corporations)', 'Category' => 'Equity', 'dataUserEntry' => 1],
            ['Account' => 'Preferred Stock', 'AccountType' => 'Stockholders\' Equity (Corporations)', 'Category' => 'Equity', 'dataUserEntry' => 1],
            ['Account' => 'Retained Earnings', 'AccountType' => 'Stockholders\' Equity (Corporations)', 'Category' => 'Equity', 'dataUserEntry' => 1],
            
            ['Account' => 'Construction of Other Civil Engineering Projects', 'AccountType' => 'Operating Revenue', 'Category' => 'Revenue', 'dataUserEntry' => 1],
            ['Account' => 'Construction Supplies', 'AccountType' => 'Sales Revenue', 'Category' => 'Revenue', 'dataUserEntry' => 1],
            
            ['Account' => 'Supplies & Materials', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Labor & Overhead', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Gasoline & Oil', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Repairs & Maintenance', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Depreciation Exp.', 'AccountType' => 'Less: Direct Cost', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            
            ['Account' => 'Amortization', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Meals and Snack', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Miscellaneous', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'dataUserEntry' => 1],
            ['Account' => 'Taxes and Licenses', 'AccountType' => 'Less: Operating Expenses', 'Category' => 'Expenses', 'dataUserEntry' => 1],
        ];
        
        foreach ($chartOfAccounts as $account) {
            ChartOfAccounts::factory()->create($account);
        }
        

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
