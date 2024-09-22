<?php

namespace Database\Seeders;

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
            ['BelongsToService' => 1, 'ServiceRequirements' => 'VALID ID', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'BIR PRINTED RECEIPT', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'REG. FEE', 'ServiceRequirementPrice' => 500, 'dataEntryUser' => '1'],
            ['BelongsToService' => 1, 'ServiceRequirements' => 'LOOSE STAMP', 'ServiceRequirementPrice' => 30, 'dataEntryUser' => '1'],
            ['BelongsToService' => 2, 'ServiceRequirements' => 'BUSINESS INFORMATION SHEET', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 2, 'ServiceRequirements' => 'OLD BUSINESS PERMIT', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 2, 'ServiceRequirements' => 'DTI', 'ServiceRequirementPrice' => 750, 'dataEntryUser' => '1'],
            ['BelongsToService' => 3, 'ServiceRequirements' => 'CASH SALES BOOKS', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 3, 'ServiceRequirements' => 'DISBURSEMENT BOOKS', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 3, 'ServiceRequirements' => 'EXPENSES RECEIPTS', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 4, 'ServiceRequirements' => 'TIN NUMBER', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 4, 'ServiceRequirements' => 'COR', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 4, 'ServiceRequirements' => 'SALES/ SERVICE INVOICE', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 4, 'ServiceRequirements' => 'NOTES TO FINANCIAL STATEMENTS', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 4, 'ServiceRequirements' => 'BIR RECEIPTS', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
            ['BelongsToService' => 4, 'ServiceRequirements' => 'INDEPENDENT AUDIT REPORT', 'ServiceRequirementPrice' => 0, 'dataEntryUser' => '1'],
        ];

        foreach ($servicesSubService as $servicesReqs) {
            if (!isset($serviceIds[$servicesReqs['BelongsToService']])) {
                throw new \Exception("Service ID not found: " . $servicesReqs['BelongsToService']);
            }

            ServicesSubTable::factory()->create([
                'BelongsToService' => $serviceIds[$servicesReqs['BelongsToService']],
                'ServiceRequirements' => $servicesReqs['ServiceRequirements'],
                'ServiceRequirementPrice' => $servicesReqs['ServiceRequirementPrice'],
                'dataEntryUser' => $servicesReqs['dataEntryUser'],
            ]);
        }
    }
}
