<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingDeadlineResource;
use App\Http\Resources\BookingImmedatelyResource;
use Illuminate\Http\Request;
use App\Models\Bookin_immediately;
use App\Models\User;
use App\Models\Booking;
use App\Models\FixingProgress;
use App\Models\Notification;

class Bookin_memediatelyController extends Controller
{
 
    public function index()
    {
        //
        $bookin_memediately = Bookin_immediately::all();
        $bookin_memediately = BookingImmedatelyResource::collection($bookin_memediately);

        return response()->json($bookin_memediately);

    }

    public function store(Request $request)
    {
        // Create a new Bookin_immediately record
        $bookin_immediately = new Bookin_immediately();
        $bookin_immediately->service_id = $request->service_id ?? null;
        $bookin_immediately->user_id = $request->user_id;
        $bookin_immediately->latitude = $request->latitude;
        $bookin_immediately->longitude = $request->longitude;
        $bookin_immediately->date = $request->date;
        $bookin_immediately->message = $request->message ?? null;
        $bookin_immediately->promotion_id = $request->promotion_id ?? null;
        $bookin_immediately->save();

        // Create a new Booking record
        $booking = new Booking();
        $booking->booking_type_id = $bookin_immediately->id;
        $booking->user_id = $request->user_id;
        $booking->type = 'immediately';
        if ($request->fixer_id) {
            $booking->fixer_id = $request->fixer_id;
        }
        $booking->save();

        // Create FixingProgress if fixer_id is provided
        if ($request->fixer_id) {
            FixingProgress::create([
                'fixer_id' => $request->fixer_id,
                'booking_id' => $booking->id,
                'action' => 'request',
            ]);

            // Update booking with fixer_id and action
            $booking->update([
                'fixer_id' => $request->fixer_id,
                'action' => 'request',
            ]);

            // Create Notification
            Notification::create([
                'user_id' => $request->user_id,
                'fixer_id' => $request->fixer_id,
                'booking_id' => $booking->id,
                'message'=> "You have booking from customer"

            ]);
        }

        return response()->json($bookin_immediately);
    }



    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}