<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Crypt;
use Random\RandomException;

class MessageService implements MessageServiceInterface

{
    /**
     * @throws RandomException
     */
    public function createMessage($data): Message
    {
        return Message::create([
            'text' => $data['text'],
            'receiver_id' => $data['receiver_id'],
            'sender_id' => auth()->id(),
            'read_once' => isset($data['read_once']) ? 1 : 0,
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }
}
