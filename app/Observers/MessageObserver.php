<?php

namespace App\Observers;

use App\Mail\MessageCreated;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        $encryptionKey = Str::random(32);
        $iv = Str::random();

        $encryptedText = openssl_encrypt($message->text, 'AES-256-CBC', $encryptionKey, 0, $iv);
        $message->iv = $iv;
        $message->text = $encryptedText;

        $message->save();

        Mail::to($message->receiver->email)->send(new MessageCreated($message, $encryptionKey));
    }
}
