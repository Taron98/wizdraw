<?php

namespace Wizdraw\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StorageAlert extends Mailable
{
    const TEST = ['juliettaa@worldcomfinance.com', 'daniell@worldcomfinance.com'];

    const PROD = ['shanib@worldcomfinance.com', 'shahar@worldcomfinance.com', 'daniell@worldcomfinance.com', 'lihayk@worldcomfinance.com'];

    protected $data;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Storage out of space';
        return $this->subject($subject)->view('emails.storage_alert')->with($this->data);
    }
}
