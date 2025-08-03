<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\BookingShow;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Bookin_immediately;
use App\Models\Bookin_deadline;
use App\Models\FixingProgress;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    public function index()
    {
        try {
            $action = 'request';
            $bookings = Booking::where('action', $action)
                ->whereNull('fixer_id')
                ->get();
            $count = $bookings->count();

            $bookingsData = BookingResource::collection($bookings);

            return response()->json([
                'bookings' => $bookingsData,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch bookings.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function store(Request $request)
    {
        //

    }
    public function show(int $id)
    {
        try {
            $actions = ['request', 'progress'];

            $bookings = Booking::where('user_id', $id)
                ->whereIn('action', $actions)
                ->get();


            $bookingsData = BookingShow::collection($bookings);

            return response()->json([
                'bookings' => $bookingsData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch bookings.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        //
        $booking = Booking::find($id);
        $booking->action = 'accepted';
        $booking->save();

        $fixingProgress = new FixingProgress();
        $fixingProgress->booking_type_id = $booking['booking_type_id'];
        $fixingProgress->type = $booking['type'];
        $fixingProgress->user_id = $booking['user_id'];
        $fixingProgress->booking_id = $id;
        if ($booking->fixer_id != null) {
            $fixingProgress->fixer_id = $booking->fixer_id;
        } else {
            $fixingProgress->fixer_id = $request->fixer_id;
        }
        $fixingProgress->type = $booking->type;
        $fixingProgress->save();

        return response()->json(['booking' => $booking, 'fixingProgress' => $fixingProgress]);
    }

    public function destroy(Request $request, string $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['error' => 'Booking not found.'], 404);
        }
        if ($booking->type == 'immediately') {
            $bookin_immediately = Bookin_immediately::find($booking->booking_type_id);
            if ($request->user_id == $bookin_immediately->user_id) {
                DB::beginTransaction();

                try {
                    $bookin_immediately->delete();
                    $booking_progress = FixingProgress::where('booking_id', $booking->id)->first();
                    if ($booking_progress) {
                        $booking_progress->delete();
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Failed to delete booking.'], 500);
                }
            }
        } else {
            $bookin_deadline = Bookin_deadline::find($booking->booking_type_id);
            if ($request->user == $bookin_deadline->user_id) {
                DB::beginTransaction();
                try {
                    $bookin_deadline->delete();
                    $booking_progress = FixingProgress::where('booking_id', $booking->id)->first();
                    if ($booking_progress) {
                        $booking_progress->delete();
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Failed to delete booking.'], 500);
                }
            }
        }
        $booking->delete();

        return response()->json(['message' => 'Booking canceled successfully']);
    }
}