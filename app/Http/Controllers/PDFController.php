<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BillingAddedDescriptions;
use App\Models\Billings;
use App\Models\Clients;
use App\Models\journal_adjustments;
use App\Models\journal_assets;
use App\Models\journal_expense;
use App\Models\journal_expense_month;
use App\Models\journal_income;
use App\Models\journal_income_months;
use App\Models\journal_liabilities;
use App\Models\journal_owners_equity;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Codedge\Fpdf\Fpdf\Fpdf;
// use Codedge\Fpdf\Facades\Fpdf;
class PDFController extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf();
    }
    public function ViewClientJournal($id){
        if(Auth::check()){
            try {
                $preparedJournalID = explode('_', $id);
                $client = Clients::where('isVisible', true)->where('id', $id)->first();
                $income = journal_income::where('journal_id', $preparedJournalID[1])->get();
                $expense = journal_expense::where('journal_id', $preparedJournalID[1])->get();
                $assets = journal_assets::where('journal_id', $preparedJournalID[1])->get();
                $liabilities = journal_liabilities::where('journal_id', $preparedJournalID[1])->get();
                $ownersEquity = journal_owners_equity::where('journal_id', $preparedJournalID[1])->get();
                $adjustments = journal_adjustments::where('journal_id', $preparedJournalID[1])->first();
                $netIncome = 0;
                // foreach ($expense as $expenses) {
                //     $expenseTotal = journal_expense_month::where('expense_id', $expenses->id)->get();
                //     Log::info($expenseTotal);
                //     // foreach ($expenseTotal as $value) {

                //     // }
                // }
                //start of statement of financial operation
                $this->fpdf->AddPage();
                $this->fpdf->SetFont('Arial', 'B', 12);
                $this->fpdf->SetY(2);
                $this->fpdf->Cell(0, 12, "Statement of Financial Position", 0, 1, 'C');

                $this->fpdf->SetY(10);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyName)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyName);

                $this->fpdf->SetY(15);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyAddress)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyAddress);

                $this->fpdf->SetY(23);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth("Statement of Financial Operation")) / 2);
                $this->fpdf->Cell(0, 10, "Statement of Financial Operation");

                $this->fpdf->SetFont('Arial', '', 8);
                $this->fpdf->SetY(30);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "For the year ended December 31");
                $this->fpdf->Line(30, 37, 210-10, 37);
                //expenses
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY(40);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, "Revenues");
                $this->fpdf->SetFont('Arial', '', 10);
                $yPosition = 45;
                $pageWidth = $this->fpdf->GetPageWidth();
                $rightMargin = 10;
                $leftMargin = 0;
                $incomeGrandTotal = 0;
                foreach ($income as $key => $value) {
                    $incomeTotal = 0;  
                    $preparedAccount = explode('_', $value['account']);
                    $incomeMonths = journal_income_months::where('income_id', $value->id)->get();
                    foreach ($incomeMonths as $incomeMonth) {
                        $incomeTotal += (float) $incomeMonth->amount;
                    }
                    $incomeGrandTotal += $incomeTotal;
                    $this->fpdf->SetY($yPosition);
                    $this->fpdf->SetX(35);
                    $this->fpdf->Cell(0, 10, $preparedAccount[1]);
                    $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                    $this->fpdf->Cell(0, 10, number_format($incomeTotal, 2), 0, 1, 'R');
                    $yPosition += 5;
                }
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($incomeGrandTotal, 2), 'T', 0, 'R');


                //expenses (costs)
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition+10);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Less: Direct Cost');
                $this->fpdf->SetFont('Arial', '', 10);
                $yPosition += 15;
                $ldcTotal = 0;
                foreach ($expense as $key => $value) {
                    $total = 0;
                    $preparedAccount = explode('_', $value['account']);
                    if(strpos($value['account'], 'Less Direct Cost') !== false){
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $preparedAccount[1]);
                        $expenseMonths = journal_expense_month::where('expense_id', $value->id)->get();
                        foreach ($expenseMonths as $expenseMonth) {
                            $total += (float) $expenseMonth->amount;
                        }
                        $ldcTotal += $total;
                        $this->fpdf->SetY($yPosition+3);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 6, number_format($total, 2), '', 0, 'R');
                    }
                    $yPosition += 5;
                }
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Direct Cost');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($ldcTotal, 2), 'T', 0, 'R');


                //total engineering costs
                $totalGrossIncome = 0;
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Gross Income from Engineering Services');
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                if($ldcTotal < $incomeGrandTotal){
                $this->fpdf->Cell(0, 10, number_format($incomeGrandTotal - $ldcTotal, 2), 'T', 0, 'R');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->Cell(0, 10, 'Total Gross Income');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, number_format($incomeGrandTotal - $ldcTotal, 2), '', 0, 'R');
                $totalGrossIncome = $incomeGrandTotal - $ldcTotal;
                }
                else{
                $this->fpdf->Cell(0, 10, number_format($ldcTotal - $incomeGrandTotal, 2), 'T', 0, 'R');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->Cell(0, 10, 'Total Gross Income');
                $this->fpdf->SetY($yPosition + 20);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, number_format($ldcTotal - $incomeGrandTotal, 2), '', 0, 'R');
                $totalGrossIncome = $ldcTotal - $incomeGrandTotal;
            }
                
                //operating expense
                $yPosition += 40;
                $loetotal = 0;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition-5);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Less: Operating Expenses');
                $this->fpdf->SetFont('Arial', '', size: 10);
                foreach ($expense as $key => $value) {
                    $total = 0;
                    $preparedAccount = explode('_', $value['account']);
                    if(strpos($value['account'], 'Operating Expenses') !== false){
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $preparedAccount[1]);
                        $expenseMonths = journal_expense_month::where('expense_id', $value->id)->get();
                        foreach ($expenseMonths as $expenseMonth) {
                            $total += (float) $expenseMonth->amount;
                        }
                        $loetotal += $total;
                        $this->fpdf->SetY($yPosition+3);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 6, number_format($total, 2), '', 0, 'R');
                    }
                    $yPosition += 5;
                }
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Operating Expense');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($loetotal, 2), 'T', 0, 'R');
                // $this->fpdf->Cell(0, 10, number_format($ldcTotal - $incomeGrandTotal), 'T', 0, 'R');

                //net income 
                $yPosition += 15;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Net Income');
                $this->fpdf->SetY($yPosition+3);
                $this->fpdf->SetX($pageWidth - $rightMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($totalGrossIncome - $loetotal, 2), 'T', 0, 'R');
                $netIncome = $totalGrossIncome - $loetotal;
                $yPosition += 15;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition + 5);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Certified True & Correct');
                // $this->fpdf->SetY($yPosition + 15);
                // $this->fpdf->SetX(30);
                $text = 'Rogelio O. Mangandam, Jr.';
                $textWidth = $this->fpdf->GetStringWidth($text) + 2;
                $this->fpdf->SetY($yPosition + 15);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, $text, 'B', 0, '');
                $this->fpdf->SetFont('Arial', '', size: 10);
                $this->fpdf->SetY($yPosition + 23);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "Proprietor");
                $this->fpdf->SetY($yPosition + 30);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "TIN: 291-273-180-000");
                //end of statement of financial operation


                //start of statement of financial position
                $yPosition = 45;
                $this->fpdf->AddPage();
                $this->fpdf->SetFont('Arial', 'B', 10);

                $this->fpdf->SetY(10);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyName)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyName);

                $this->fpdf->SetY(15);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth($client->CompanyAddress)) / 2);
                $this->fpdf->Cell(0, 10, $client->CompanyAddress);

                $this->fpdf->SetY(23);
                $this->fpdf->SetX((210 - $this->fpdf->GetStringWidth("Statement of Financial Position")) / 2);
                $this->fpdf->Cell(0, 10, "Statement of Financial Position");

                $this->fpdf->SetFont('Arial', '', 8);
                $this->fpdf->SetY(30);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "as of December 31");
                $this->fpdf->Line(30, 37, 210-10, 37);


                //assets
                //Current Assets
                $currentAssetsTotal = 0;
                $fixedAssetsTotal = 0;
                $nonCurrentAssetsTotal = 0;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "Current Assets");
                $this->fpdf->SetFont('Arial', '', 10);

                foreach ($assets as $value) {
                    if ($value['asset_category'] === 'Current Asset') {
                        $yPosition += 5;
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $value['account']);
                        $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                        $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                        $currentAssetsTotal += (float)$value['amount'];
                    }
                }

                $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $yPosition += 6;
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Current Assets');
                $this->fpdf->SetY($yPosition + 2);
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($currentAssetsTotal, 2));
                $this->fpdf->Line(30, $yPosition + 7, 210-10, $yPosition + 7);
                //Current Assets

                //non-current assets
                $yPosition += 10;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "Non-Current Assets");
                $this->fpdf->SetFont('Arial', '', 10);

                foreach ($assets as $value) {
                    if ($value['asset_category'] === 'Non-Current Assets') {
                        $yPosition += 5;
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $value['account']);
                        $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                        $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                        $nonCurrentAssetsTotal += (float)$value['amount'];
                    }
                }

                $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $yPosition += 6;
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Total Non-Current Assets');
                $this->fpdf->SetY($yPosition + 2);
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 6, number_format($nonCurrentAssetsTotal, 2));
                $this->fpdf->Line(30, $yPosition + 7, 210-10, $yPosition + 7);
                //non-current assets

                //fixed assets
                $yPosition += 10;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Line(30, 32, 210-10, 32);
                $this->fpdf->Cell(0, 10, "Fixed Assets");
                $this->fpdf->SetFont('Arial', '', 10);

                foreach ($assets as $value) {
                    if ($value['asset_category'] === 'Fixed Assets') {
                        $yPosition += 5;
                        $this->fpdf->SetY($yPosition);
                        $this->fpdf->SetX(35);
                        $this->fpdf->Cell(0, 10, $value['account']);
                        $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                        $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                        $fixedAssetsTotal += (float)$value['amount'];
                    }
                }

                // $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                // $this->fpdf->SetFont('Arial', 'B', 10);
                // $yPosition += 6;
                // $this->fpdf->SetY($yPosition);
                // $this->fpdf->SetX(35);
                // $this->fpdf->Cell(0, 10, 'Total Fixed Assets');
                // $this->fpdf->SetY($yPosition + 2);
                // $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                // $this->fpdf->Cell(0, 6, number_format($fixedAssetsTotal, 2));
                // $this->fpdf->Line(30, $yPosition + 7, 210-10, $yPosition + 7);
                $yPosition += 10;
                $totalAssets = $currentAssetsTotal + $fixedAssetsTotal + $nonCurrentAssetsTotal;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Line(30, $yPosition + 3, 210-10, $yPosition + 3);
                $this->fpdf->Line(30, $yPosition + 9, 210-10, $yPosition + 9);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Total Assets');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 10, number_format($totalAssets, 2));
                $this->fpdf->Line(30, $yPosition + 8, 210-10, $yPosition + 8);

                //liabilities
                $yPosition += 10;
                $liabilitiesTotal = 0;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Current Liabilities');

                foreach ($liabilities as $value) {
                    // $yPosition += 5;
                    // $this->fpdf->SetFont('Arial', '', 10);
                    // $this->fpdf->SetY($yPosition);
                    // $this->fpdf->SetX(35);
                    // $this->fpdf->Cell(0, 10, $value['account']);
                    // $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                    // $this->fpdf->Cell(0, 10, txt: number_format($value['amount'], 2));
                    $liabilitiesTotal += (float) $value['amount'];
                }
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 10, number_format($liabilitiesTotal,2));
                //liabilities

                //owners equity/net worth
                $yPosition += 10;
                $ownersEquityTotal = 0;
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, "Owner"."'"."s Equity / Net Worth");
                //owners equity/net worth
                foreach ($ownersEquity as $value) {
                    $yPosition += 5;
                    $this->fpdf->SetFont('Arial', '', 10);
                    $this->fpdf->SetY($yPosition);
                    $this->fpdf->SetX(35);
                    $this->fpdf->Cell(0, 10, $value['account']);
                    $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                    $this->fpdf->Cell(0, 10, number_format($value['amount'], 2));
                    $ownersEquityTotal += (float) $value['amount'];
                }

                $yPosition += 10;
                $this->fpdf->SetY($yPosition);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Add: Net Increase to Capital');
                $this->fpdf->SetY($yPosition + 5);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Additional Capital');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($adjustments->owners_contribution, 2));
                $this->fpdf->SetFont('Arial', '', 10);
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(35);
                $this->fpdf->Cell(0, 10, 'Net Income');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($netIncome, 2));
                $this->fpdf->Line(30, $yPosition + 18, 210-10, $yPosition + 18);
                $this->fpdf->SetFont('Arial', '', 10);
                $yPosition += 10;
                $this->fpdf->SetY($yPosition + 6);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Appraisal Capital');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($netIncome + $adjustments->owners_withdrawal, 2));
                $appraisalCapital = $netIncome + $adjustments->owners_withdrawal;
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', '', 10);
                $this->fpdf->Cell(0, 10, 'Less: Drawings');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($adjustments->owners_withdrawal, 2));
                $this->fpdf->SetY($yPosition + 15);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', '', 10);
                $this->fpdf->Cell(0, 10, 'Capital, end');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, number_format($appraisalCapital - $adjustments->owners_withdrawal, 2));

                $yPosition += 10;
                $this->fpdf->SetY($yPosition + 10);
                $this->fpdf->SetX(30);
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->Cell(0, 10, 'Total Liabilities & Capital');
                $this->fpdf->SetX($pageWidth - $leftMargin - 30);
                $this->fpdf->Cell(0, 10, number_format($liabilitiesTotal + $ownersEquityTotal, 2));
                $this->fpdf->Line(30, $yPosition + 13, 210-10, $yPosition + 13);
                $this->fpdf->Line(30, $yPosition + 18, 210-10, $yPosition + 18);

                $yPosition += 15;
                $this->fpdf->SetFont('Arial', 'B', size: 10);
                $this->fpdf->SetY($yPosition + 5);
                $this->fpdf->SetX(30);
                $this->fpdf->Cell(0, 10, 'Certified True & Correct');
                
                $text = 'Rogelio O. Mangandam, Jr.';
                $textWidth = $this->fpdf->GetStringWidth($text) + 2;
                $this->fpdf->SetY($yPosition + 15);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, $text, 'B', 0, '');
                $this->fpdf->SetFont('Arial', '', size: 10);
                $this->fpdf->SetY($yPosition + 23);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "Proprietor");
                $this->fpdf->SetY($yPosition + 30);
                $this->fpdf->SetX($leftMargin + 30);
                $this->fpdf->Cell($textWidth, 6, "TIN: 291-273-180-000");
                $this->fpdf->Output('I', 'example.pdf');
                exit;
        
               
            } catch (\Throwable $th) {
                Log::error('Error generating PDF: ' . $th->getMessage());
                throw $th;
            }
        } else {
            Log::warning('User not authenticated');
        }
    }
    
    public function ClientBillingData($billingID){
        if(Auth::check()){
            try {
                $billing = Billings::where('billing_id', $billingID)->first();
                $client = Clients::where('id', $billing->client_id)->first();
                $addedDescriptions = BillingAddedDescriptions::where('billing_id', $billingID)->get();
                $servicesData = DB::table('billings')
                    ->join('client_billing_services', 'client_billing_services.billing_id', '=', 'billings.billing_id')
                    ->join('services', 'services.id', '=', 'client_billing_services.service')
                    ->where('billings.billing_id', $billingID)
                    ->select('services.id as service_id', 'services.Service as service_name', 'services.Price as service_price')
                    ->get();

                $servicesHierarchy = [];

                foreach ($servicesData as $service) {
                    $subServicesData = DB::table('client_billing_sub_services')
                        ->join('services_sub_tables', 'services_sub_tables.id', '=', 'client_billing_sub_services.sub_service')
                        ->where('services_sub_tables.BelongsToService', $service->service_id)
                        ->select(
                            'services_sub_tables.ServiceRequirements as sub_service_name',
                            'services_sub_tables.ServiceRequirementPrice as sub_service_price',
                            'services_sub_tables.id as sub_service_id'
                        )
                        ->get();
                    $servicesHierarchy[$service->service_id] = [
                        'service_name' => $service->service_name,
                        'service_price' => $service->service_price,
                        'sub_services' => []
                    ];
                    foreach ($subServicesData as $subService) {
                        $servicesHierarchy[$service->service_id]['sub_services'][$subService->sub_service_id] = [
                            'sub_service_name' => $subService->sub_service_name,
                            'sub_service_price' => $subService->sub_service_price,
                            'account_descriptions' => []
                        ];
                        $accountDescriptions = DB::table('billing_descriptions')
                            ->select(
                                'account_descriptions.Description as account_description',
                                'account_descriptions.Price as account_price'
                            )
                            ->join('account_descriptions', 'billing_descriptions.description', '=', 'account_descriptions.id')
                            ->join('services_sub_tables', 'services_sub_tables.id', '=', 'account_descriptions.account')
                            ->where('billing_descriptions.billing_id', $billingID)
                            ->where('services_sub_tables.id', $subService->sub_service_id)
                            ->get();
                        $servicesHierarchy[$service->service_id]['sub_services'][$subService->sub_service_id]['account_descriptions'] = $accountDescriptions;
                    }
                }

                $leftMargin = 15;
                $rightMargin = 15;
                $pageWidth = 210;
                $usableWidth = $pageWidth - $leftMargin - $rightMargin;

                $columnWidth = ($usableWidth * 0.7);
                $priceWidth = ($usableWidth * 0.3);

                $this->fpdf->AddPage();
                $this->fpdf->SetFont('Arial', '', 10);

                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetX($leftMargin);
                $this->fpdf->SetY(10);
                $this->fpdf->Cell($columnWidth, 10, "Billed to: \t" . $client->CompanyName, 0, 1, 'L');
                $this->fpdf->SetY(15);
                $this->fpdf->Cell($columnWidth, 10, "Due: \t" . $billing->due_date, 0, 1, 'L');
                $this->fpdf->SetY(20);
                $this->fpdf->Cell($columnWidth, 10, "Billing ID: \t" . $billing->billing_id, 0, 1, 'L');

                $this->fpdf->SetFillColor(200, 200, 200);
                $this->fpdf->SetX($leftMargin);
                $this->fpdf->Cell($columnWidth, 10, 'Services', 1, 0, 'C', true);
                $this->fpdf->Cell($priceWidth, 10, 'Amount', 1, 1, 'C', true);

                $grandTotal = 0;

                foreach ($servicesHierarchy as $service) {
                    $serviceTotal = $service['service_price'];

                    $this->fpdf->SetFont('Arial', 'B', 10);
                    $this->fpdf->SetX($leftMargin);
                    $this->fpdf->Cell($columnWidth, 8, "\t\t\t\t\t\t$service[service_name]", 1);
                    $this->fpdf->Cell($priceWidth, 8, number_format($service['service_price'], 2), 1, 1, 'R');

                    foreach ($service['sub_services'] as $subService) {
                        $serviceTotal += $subService['sub_service_price'];

                        $this->fpdf->SetFont('Arial', '', 10);
                        $this->fpdf->SetX($leftMargin);
                        $this->fpdf->Cell($columnWidth, 8, "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t$subService[sub_service_name]", 1);
                        $this->fpdf->Cell($priceWidth, 8, number_format($subService['sub_service_price'], 2), 1, 1, 'R');

                        foreach ($subService['account_descriptions'] as $accountDescription) {
                            $serviceTotal += $accountDescription->account_price;

                            $this->fpdf->SetX($leftMargin);
                            $this->fpdf->Cell($columnWidth, 8, "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t$accountDescription->account_description", 1);
                            $this->fpdf->Cell($priceWidth, 8, number_format($accountDescription->account_price, 2), 1, 1, 'R');
                        }
                    }

                    // $this->fpdf->SetFont('Arial', 'B', 10);
                    // $this->fpdf->SetX($leftMargin);
                    // $this->fpdf->Cell($columnWidth, 8, "        Total for " . $service['service_name'] . ":", 1);
                    // $this->fpdf->Cell($priceWidth, 8, number_format($serviceTotal, 2), 1, 1, 'R');

                    $grandTotal += $serviceTotal;

                    if ($this->fpdf->GetY() > 270) {
                        $this->fpdf->AddPage();
                        $this->fpdf->SetX($leftMargin);

                        $this->fpdf->SetFillColor(200, 200, 200);
                        $this->fpdf->Cell($columnWidth, 10, 'Services', 1, 0, 'C', true);
                        $this->fpdf->Cell($priceWidth, 10, 'Amount', 1, 1, 'C', true);
                    }
                }

                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetX($leftMargin);
                $this->fpdf->Cell($columnWidth, 10, "Total", 1, 0, '');
                $this->fpdf->Cell($priceWidth, 10, number_format($grandTotal, 2), 1, 1, 'R');


                if ($addedDescriptions->isNotEmpty()) {
                    $this->fpdf->SetFont('Arial', 'B', 10);
                    $this->fpdf->SetX($leftMargin);
                    $this->fpdf->Cell($columnWidth, 10, "Added Descriptions", 1, 0, 'C', true);
                    $this->fpdf->Cell($priceWidth, 10, "Amount", 1, 1, 'C', true);
    
                    $addedDescriptionsTotal = 0;
                    foreach ($addedDescriptions as $description) {
                        $addedDescriptionsTotal += $description->amount;
    
                        $this->fpdf->SetFont('Arial', '', 10);
                        $this->fpdf->SetX($leftMargin);
                        $this->fpdf->Cell($columnWidth, 8, "\t\t\t\t\t\t$description->account", 1);
                        $this->fpdf->Cell($priceWidth, 8, number_format($description->amount, 2), 1, 1, 'R');
                    }
    
                    $this->fpdf->SetFont('Arial', 'B', 10);
                    $this->fpdf->SetX($leftMargin);
                    $this->fpdf->Cell($columnWidth, 10, "Total for Added Descriptions", 1, 0, '');
                    $this->fpdf->Cell($priceWidth, 10, number_format($addedDescriptionsTotal, 2), 1, 1, 'R');
                }
    
                $this->fpdf->SetFont('Arial', 'B', 10);
                $this->fpdf->SetX($leftMargin);
                $this->fpdf->Cell($columnWidth, 10, "Total", 1, 0, '');
                $this->fpdf->Cell($priceWidth, 10, number_format($grandTotal + $addedDescriptionsTotal, 2), 1, 1, 'R');
    
                // Output the PDF
                $this->fpdf->Output();
                exit;
            } catch (\Throwable $th) {
                throw $th;
            }
        }else{
            dd('unauthorized access');
        }
    }
}