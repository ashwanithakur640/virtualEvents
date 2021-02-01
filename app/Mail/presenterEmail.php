<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;
class presenterEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	 private $message,$details,$user ,$url;
    public function __construct($message,$details , $url)
    {
        //
		$this->message = $message;
		$this->details = $details;
		$this->user = User::find($details->user_id);
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Invited to present')->view('emails.presenter')->with(['message_user'=>$this->message,'detail' => $this->details,'user' => $this->user, 'url' => $this->url ]);
    }
}
