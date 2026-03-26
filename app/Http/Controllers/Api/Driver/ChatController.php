<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\ChatInbox;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Create/update chat inbox.
     */
    public function inbox(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
            'driver_id' => 'required|string',
            'customer_id' => 'required|string',
            'last_message' => 'nullable|string',
        ]);

        $inbox = ChatInbox::updateOrCreate(
            ['order_id' => $request->order_id],
            [
                'driver_id' => $request->driver_id,
                'customer_id' => $request->customer_id,
                'last_message' => $request->last_message,
                'last_message_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Chat inbox updated successfully.',
            'data' => $inbox,
        ]);
    }

    /**
     * Send chat message.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
            'sender_id' => 'required|string',
            'sender_type' => 'required|string|in:driver,customer',
            'message' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $message = ChatMessage::create([
            'order_id' => $request->order_id,
            'sender_id' => $request->sender_id,
            'sender_type' => $request->sender_type,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
        ]);

        // Update the inbox with the last message
        ChatInbox::where('order_id', $request->order_id)->update([
            'last_message' => $request->message,
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $message,
        ]);
    }

    /**
     * Get chat messages for order (paginated).
     */
    public function messages(Request $request, string $orderId): JsonResponse
    {
        $perPage = $request->per_page ?? 50;

        $messages = ChatMessage::where('order_id', $orderId)
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Messages retrieved successfully.',
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }
}
