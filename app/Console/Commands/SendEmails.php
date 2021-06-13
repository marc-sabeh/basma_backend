<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Email every day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $number= User::select(DB::raw('count(*) as number_of_registration'))
        ->whereBetween('created_at', [Carbon::now()->subMinutes(1440), Carbon::now()])
        ->get();

        $data = array('body' =>$number[0]->number_of_registration);
        $sendmail=  Mail::send('emails.welcome', $data, function($message) {
            $email='sabehmarc@outlook.com';
            $name ='Admin';
            $message->to($email , $name)->subject('New user sent Contact Form');
         });

        if (empty($sendmail)) { 
            $this->info('Mail Sent Sucssfully');
        } else{
             $this->info('Mail Sent fail');
        }
    }
}
