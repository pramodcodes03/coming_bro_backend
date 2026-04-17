<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\ChatInbox;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function inboxes(Request $request): JsonResponse
    {
        $inboxes = ChatInbox::where('customer_id', $request->user()->id)
            ->orderBy('last_message_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Chat inboxes retrieved.',
            'data' => $inboxes,
        ]);
    }

    public function inbox(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'last_message' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'driver_profile_image' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_profile_image' => 'nullable|string',
        ]);

        $inbox = ChatInbox::updateOrCreate(
            ['order_id' => $request->order_id],
            [
                'driver_id' => $request->driver_id,
                'customer_id' => $request->customer_id,
                'last_message' => $request->last_message,
                'driver_name' => $request->driver_name ?? '',
                'driver_profile_image' => $request->driver_profile_image ?? '',
                'customer_name' => $request->customer_name ?? '',
                'customer_profile_image' => $request->customer_profile_image ?? '',
                'last_message_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Chat inbox updated.',
            'data' => $inbox,
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'message' => 'nullable|string',
            'type' => 'nullable|string',
            'url_url' => 'nullable|string',
            'url_mime' => 'nullable|string',
            'url_video_thumbnail' => 'nullable|string',
            'video_thumbnail' => 'nullable|string',
        ]);

        $customer = $request->user();

        $message = ChatMessage::create([
            'order_id' => $request->order_id,
            'sender_id' => $customer->id,
            'sender_type' => 'customer',
            'message' => $request->message ?? '',
            'type' => $request->type ?? 'text',
            'message_type' => $request->type ?? 'text',
            'url_url' => $request->url_url ?? '',
            'url_mime' => $request->url_mime ?? '',
            'url_video_thumbnail' => $request->url_video_thumbnail,
            'video_thumbnail' => $request->video_thumbnail ?? '',
        ]);

        ChatInbox::where('order_id', $request->order_id)->update([
            'last_message' => $request->message ?? 'Attachment',
            'last_message_at' => now(),
            'last_sender_id' => $customer->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent.',
            'data' => $message,
        ]);
    }

    public function messages(Request $request, string $orderId): JsonResponse
    {
        $perPage = $request->per_page ?? 50;

        $messages = ChatMessage::where('order_id', $orderId)
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Messages retrieved.',
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate(['file' => 'required|file|max:51200']);

        $path = $request->file('file')->store('chat', 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded.',
            'data' => ['url' => $url, 'mime' => $request->file('file')->getMimeType()],
        ]);
    }
}
