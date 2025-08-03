<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Booking;
use App\Models\FixingProgress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $notifications = Notification::all();
    return response()->json([
        'data' => $notifications,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'fixer_id' => 'required|integer',
            'booking_id' => 'required|integer',
        ]);

        try {
            // Create a new Notification instance
            $notification = new Notification();
            $notification->customer_id = $validatedData['user_id'];
            $notification->fixer_id = $validatedData['fixer_id'];
            $notification->booking_id = $validatedData['booking_id'];
            $notification->save();

            // Optionally, you can return a response or redirect after successful save
            return response()->json(['message' => 'Notification created successfully'], 201);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during save
            return response()->json(['message' => 'Failed to create notification', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $notifications = Notification::where('fixer_id', $id)
        ->where('action',0)
        ->get();
        $notifications = NotificationResource::collection($notifications);
    
        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'Notifications not found for the specified fixer_id.',
            ], 404);
        }
    
        return response()->json([
            'data' => $notifications,
        ]);
    }
    public function customerNotification(int $id)
    {
        $notifications = Notification::where('user_id', $id)
        ->where('action',2)
        ->get();
        $notifications = NotificationResource::collection($notifications);
    
        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'Notifications not found for the specified user_id.',
            ], 404);
        }
    
        return response()->json([
            'data' => $notifications,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id)
    {
        // Find the notification record
        $notification = Notification::find($id);
    
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
    
        // Find the fixing progress record based on the booking_id from notification
        $fixingProgress = FixingProgress::where('booking_id', $notification->booking_id)->first();
    
        if ($fixingProgress) {
            // Update the action status for fixing progress
            $fixingProgress->action = 'progress';
            $fixingProgress->save();
        }
    
        $booking = Booking::findOrFail($notification->booking_id);
        $booking->action = 'progress';
        $booking->save();
    
        Notification::where('booking_id', $notification->booking_id)->update(['action' => 1]);
        $newNotification = Notification::create([
            'user_id' => $notification->user_id,
            'fixer_id' => $notification->fixer_id,
            'booking_id' => $notification->booking_id,
            'action' => 2,
            'message' => 'Your booking has been accepted by the fixer'
        ]);
        return response()->json([
            'booking' => $booking,
            'fixingProgress' => $fixingProgress,
            'notification_updated' => true,
            'notification' =>$newNotification
        ]);
    }
    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the notification by ID
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
        
        // Delete the related FixingProgress if it exists
        $fixingProgress = FixingProgress::where('booking_id', $notification->booking_id)->first();
        if ($fixingProgress) {
            $fixingProgress->delete();
        }
        
        $booking = Booking::find($notification->booking_id);
        if ($booking) {
            $booking->delete();
        }
    
        $newNotification = Notification::create([
            'user_id' => $notification->user_id,
            'fixer_id' => $notification->fixer_id,
            'booking_id' => $notification->booking_id,
            'action' => 2,
            'message' => 'Your booking has been rejected by the fixer'
        ]);
        
        $notification->delete();
        
    
        // Optionally delete any other notifications related to the same booking_id
        // Notification::where('booking_id', $notification->booking_id)->delete();
    
        return response()->json([
            'message' => 'Notification deleted and new notification created successfully',
            'notification' => $newNotification
        ]);
    }
    

}
