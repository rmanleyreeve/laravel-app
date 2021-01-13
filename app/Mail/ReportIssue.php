<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
useIlluminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportIssue extends Mailable
{
    use Queueable, SerializesModels;

	public $content;
	public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	return $this->view('report-email');
    }
}
