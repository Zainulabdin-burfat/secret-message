<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Message $message;
    public string $encryptionKey;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function __construct(Message $message, string $encryptionKey)
    {
        $this->message = $message;
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Message Created')
            ->view('emails.message-created')
            ->with([
                'sender_name' => $this->message->sender->name,
                'message_id' => $this->message->id,
                'encryption_key' => $this->encryptionKey,
            ]);
    }
}
