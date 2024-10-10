<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailClientServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class MailerController extends Controller
{
    //
    public function MailClientServices(Request $request){
        if (Auth::check()) {
            $sendTo = 'lagrosaedrian06@gmail.com';

            Mail::to($sendTo)->send(new MailClientServices('test', 'test email'));
        }else{
            dd('Unauthorized Access');
        }
    }
}
