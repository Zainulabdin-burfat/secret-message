<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageShowRequest;
use App\Http\Requests\MessageStoreRequest;
use App\Services\MessageServiceInterface;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Random\RandomException;

class MessageController extends Controller
{
    protected MessageServiceInterface $messageService;

    public function __construct(MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
    }

    public function store(MessageStoreRequest $request)
    {
        $this->messageService->createMessage($request->validated());

        return redirect()->to('dashboard')->with(['message' => 'Message sent successfully!', 'status' => true]);
    }

    public function show(MessageShowRequest $request)
    {
        $message = Message::findOrFail($request->input('message_id'));

        // Check if the authenticated user is the recipient of the message
        if ($message->receiver_id !== Auth::id()) {
            return redirect()->route('dashboard')->with([
                'message' => 'Unauthorized access to the message',
                'status' => false
            ]);
        }

        // Initialize response data
        $data = [
            'message' => 'Message could not be retrieved',
            'status' => false
        ];

        // Check if the message has expired
        if ($message->expires_at && $message->expires_at < now()) {
            $message->delete();
            $data['message'] = 'Message expired';
            return redirect()->route('dashboard')->with($data);
        }

        // Attempt to decrypt the message text
        $decryptionKey = $request->input('key');
        $decryptedText = openssl_decrypt($message->text, 'AES-256-CBC', $decryptionKey, 0, $message->iv);

        // Handle invalid decryption key
        if ($decryptedText === false) {
            $data['message'] = 'Invalid decryption key';
            return redirect()->route('dashboard')->with($data);
        }

        // Prepare the successful response data
        $data['message'] = $decryptedText;
        $data['status'] = true;

        // Handle read-once messages
        if ($message->read_once) {
            $message->delete();
            $data['message'] .= ' (Note: This is a read-once message and has been deleted)';
        }

        // Redirect with the response data
        return redirect()->route('dashboard')->with($data);
    }


}
