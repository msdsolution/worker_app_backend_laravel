<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $clientName;
    public $message;
    public $pdfPath;
    public function __construct($clientName = '', $message = '', $pdfPath)
    {
        //
        $this->clientName = $clientName;
        $this->message = (string) $message;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.invoice.email',
        );
    }
    public function build()
    {

        return $this->subject('Invoice Mail')
                    ->view('invoice.email')  // Use your view path here
                    ->with([
                        'clientName' => $this->clientName,
                        'message' => "Please find the attached invoice.",
                    ])
                    ->attach($this->pdfPath, [
                        'as' => 'invoice.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [
    //         new Attachment(
    //             path: $this->pdfPath,
    //             as: 'invoice.pdf',
    //             mime: 'application/pdf'
    //         ),
    //     ];
    // }
}
