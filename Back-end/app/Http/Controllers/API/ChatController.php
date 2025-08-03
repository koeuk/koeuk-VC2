<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $chats = Chat::all();
        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        // $validator = Validator::make($request->all(), [
        //     'sender_id' => 'required|integer',
        //     'receiver_id' => 'required|integer',
        //     'message' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 400);
        // }

        // Process the image file
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $base64Image = base64_encode(file_get_contents($image->path()));

        //     // Store in database
        // }
        // Chat::create([
        //     'sender_id' => $request->sender_id,
        //     'receiver_id' => $request->receiver_id,
        //     'message' => $request->message,
        // ]);

        return "Chat created successfully";

    }

    /**
     * Display the specified resource.
     */
    public function show(string $userId)
    {
        // Fetch chats where sender_id is equal to the user_id
        $chats = Chat::where('sender_id', $userId)
                     ->orWhere('receiver_id', $userId)
                     ->get();
    
        if ($chats->isEmpty()) {
            return response()->json(['message' => 'No chats found', 'data' => []], 404);
        }
    
        return response()->json(['message' => 'Chats retrieved successfully', 'data' => $chats]);
    }
    


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        Chat::find($id)->update(
            [
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
                'image' => $request->image,
                'is_read' => $request->is_read,
            ]
        );
        return "Chat updated successfully";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Chat::find($id)->delete();
        return "Chat deleted successfully";
    }

    public function getChatsBetweenUsers($senderId, $receiverId)
    {
        try {
            $chats = Chat::where(function($query) use ($senderId, $receiverId) {
                            $query->where('sender_id', $senderId)
                                  ->where('receiver_id', $receiverId);
                        })
                        ->orWhere(function($query) use ($senderId, $receiverId) {
                            $query->where('sender_id', $receiverId)
                                  ->where('receiver_id', $senderId);
                        })
                        ->get();

            if ($chats->isEmpty()) {
                return response()->json(['message' => 'No chats found for the given sender and receiver IDs'], 404);
            }

            return response()->json($chats, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while retrieving chats'], 500);
        }
    }

}
