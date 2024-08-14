<?php

namespace App\Console\Commands;

use App\Mail\SessionAlert;
use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSessionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-session-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron will send notification email to students before 5 minutes of scheduled session';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Send Session Email Cron starts');
        $sessions = ScheduleSession::where('state_id', ScheduleSession::STATE_ACTIVE)->get();
        foreach ($sessions as $session) {
            $time = date('Y-m-d H:i:s', strtotime($session->start_time . '-5 minutes'));
            if (strtotime(date('Y-m-d H:i:s')) >= $time) {
                $user = User::find($session->user_id);
                Mail::to($user->email)->send(new SessionAlert());
                $this->info('Send Session Email sends');
                $session->update(['state_id' => ScheduleSession::STATE_NOTIFY]);
            }
        }
        $this->info('Send Session Email Cron ends');
    }
}
