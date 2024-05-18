<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaymentUpload extends Mailable
{
    use Queueable, SerializesModels;

    protected $type;
    protected $booking;
    protected $user;
    protected $pic;

    /**
     * Create a new message instance.
     */
    public function __construct($type, $booking, $user, $pic)
    {
        $this->type = $type;
        $this->booking = $booking;
        $this->user = $user;
        $this->pic = $pic;
    }

    public function build()
    {
        $type = $this->type;
        $booking = $this->booking;
        $user = $this->user;
        $pic = $this->pic;
        return $this->subject('Requisition (' . $type . ') from EMP_ID - ' . $user->eid)
            ->view('mail.index', compact('type', 'booking', 'user', 'pic'));
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         from: new Address('employee.purchase@fel.com.bd', 'FEL'),
    //         replyTo: [
    //             new Address('md.rabby.mahmud@gmail.com', 'M01418'),
    //         ],
    //         subject: 'Order Payment Upload',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'mail.index',
    //         with: [
    //             'order' => $this->order,
    //             'payment' => $this->payment,
    //         ],
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments()
    // {
    //     // return [public_path('file_url/' . $pic)];
    //     [
    //         Attachment::fromStorage('/lol.png')
    //     ];
    // }
}
