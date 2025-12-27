<?php

namespace App\Jobs;

use App\Helpers\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailForgotPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email = null;
    public $token = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $token) {
        //
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        //
        $url_forgot_password = route("cms.forgot_password.attempt", [
            "email" => $this->email,
            "token" => $this->token,
        ]);
        $body_html = view("forgot_password.email_forgot_password", [
            "url_forgot_password" => $url_forgot_password,
        ])->render();
        $result = EmailHelper::sendEmail($this->email, $this->email, "Reset Your Dashboard Password", $body_html);
    }
}
