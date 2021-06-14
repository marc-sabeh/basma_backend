<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function number_of_users(Request $request){

        $users= User::where('id', 'LIKE', '%' .request('id'). '%')
        ->where('name', 'LIKE', '%' .request('name'). '%')
        ->where('email', 'LIKE', '%' .request('email'). '%')
        ->paginate(request('pagination'));

        return response()->json($users);  
    }

    public function average_of_users(Request $request){

        $from = request('from');
        $to = request('to');
        $period = request('period');
      

        $average= User::select(DB::raw('count(*) as number_of_registration'));
        if(isset($from) && isset($to))
        {
            $average->whereBetween('created_at', [$from, $to]);
            
        }
        if($period == 'last24'){
            $average->whereBetween('created_at', [Carbon::now()->subMinutes(1440), Carbon::now()]);
        }
        else if($period == 'lastweek'){
            $average->whereBetween('created_at', [Carbon::now()->subdays(7), Carbon::now()]);
        }
        else if($period == 'lastmonth'){
            $average->whereBetween('created_at', [Carbon::now()->subdays(30), Carbon::now()]);
        }
        else if($period == 'last3month'){
            $average->whereBetween('created_at', [Carbon::now()->subdays(90), Carbon::now()]);
        }
        else if($period == 'lastyear'){
            $average->whereBetween('created_at', [Carbon::now()->subdays(365), Carbon::now()]);
        }
        $results = $average->get();

        $number= User::select(DB::raw('count(*) as number_of_registration'))
        ->whereBetween('created_at', [Carbon::now()->subMinutes(1440), Carbon::now()])
        ->get();
        // $this->info($number);

        $data = array('body' => $number);
        $sendmail=  Mail::send('emails.welcome', $data, function($message) {
            $email='sabehmarc@outlook.com';
            $name ='Admin';
            $message->to($email , $name)->subject('New user sent Contact Form');
         });

        return response()->json(['Average' => $results]);
    }
}
