<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\journal_expense;
use App\Models\journal_income;
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
                $income = journal_income::where('journal_id', $preparedJournalID[1])->get();
                $expense = journal_expense::where('journal_id', $preparedJournalID[1])->get();
                $this->fpdf->AddPage();
                $this->fpdf->SetFont('Arial', 'B', 16);
                $this->fpdf->Cell(40, 10, 'Hello World');
                $this->fpdf->Output('I', 'example.pdf');
                exit;
        
               
            } catch (\Throwable $th) {
                // Handle errors
                Log::error('Error generating PDF: ' . $th->getMessage());
                throw $th;
            }
        } else {
            // Handle if the user is not authenticated
            Log::warning('User not authenticated');
        }
    }
    
    
}
