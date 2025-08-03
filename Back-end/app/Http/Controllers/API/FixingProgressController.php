<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingShow;
use App\Http\Resources\FixingProgressResource;
use App\Models\Bookin_deadline;
use App\Models\Bookin_immediately;
use App\Models\Booking;
use App\Models\FixingProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FixingProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $fixingProgress = FixingProgress::all();
        return response()->json([
            'fixingProgress' => $fixingProgress
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'fixer_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        try {
            $fixingProgress = FixingProgress::create([
                'fixer_id' => $request->fixer_id,
                'booking_id' => $request->booking_id,
                'action' => 'progress',
            ]);

            $booking = Booking::findOrFail($request->booking_id);
            $booking->action = 'progress';
            $booking->fixer_id = $request->fixer_id;
            $booking->save();

            return response()->json([
                'message' => 'FixingProgress created and booking updated successfully!',
                'data' => $fixingProgress
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create FixingProgress or update booking!',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function update(Request $request, string $id)
    {

        $fixingProgress = FixingProgress::find($id)->where;
        $fixingProgress->action = $request->action;

        $fixingProgress->save();

        return response()->json($fixingProgress);
    }
    public function cancelAccept(Request $request, string $id)
    {
        $fixingProgress = FixingProgress::find($id);
        $booking = Booking::findOrFail($request->booking_id);
        $booking->action = 'request';
        $booking->fixer_id = null;
        $booking->save();
        $fixingProgress->delete();

        return response()->json($fixingProgress);
    }
    public function show($id)
    {
        try {
            $acceptedBookings = FixingProgress::where('fixer_id', $id)
            ->where('action','progress')
                ->get();
                $count = $acceptedBookings->count();
            return response()->json([
                'success' => true,
                'accepted_bookings' => $acceptedBookings,
                'count'=>$count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fixer not found.'
            ], 404);
        }
    }
    public function startFixer(string $id)
    {
        // Retrieve the fixing progress record
        $fixingProgress = FixingProgress::find($id);
        if (!$fixingProgress) {
            return response()->json(['error' => 'Fixing progress not found'], 404);
        }

        // Retrieve the associated booking record
        $booking = Booking::find($fixingProgress->booking_id);
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        // Determine the booking type and query the appropriate table
        $bookingType = $booking->booking_type_id;

        if ($booking->type === 'immediately') {
            $bookDetails = Bookin_immediately::find($bookingType);
        } elseif ($booking->type === 'deadline') {
            $bookDetails = Bookin_deadline::find($bookingType);
        } else {
            return response()->json(['error' => 'Invalid booking type'], 400);
        }

        if (!$bookDetails) {
            return response()->json(['error' => 'Booking details not found'], 404);
        }

        DB::beginTransaction();

        try {
            // Update the action status for both fixing progress and booking
            $fixingProgress->action = 'progress';
            $fixingProgress->save();

            $booking->action = 'progress';
            $booking->save();

            DB::commit();

            return response()->json([
                'message' => 'Fixing process started successfully',
                'fixingProgress' => $fixingProgress,
                'latitude' => $bookDetails->latitude,
                'longitude' => $bookDetails->longitude,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while starting the fixing process', 'details' => $e->getMessage()], 500);
        }
    }

    public function doneFixer(string $id)
{
    $fixingProgress = FixingProgress::find($id);
    if (!$fixingProgress) {
        return response()->json(['error' => 'Fixing progress not found'], 404);
    }

    DB::beginTransaction();

    try {
        $fixingProgress->action = 'done';
        $fixingProgress->save();

        $booking = Booking::findOrFail($fixingProgress->booking_id);
        $booking->action = 'done';
        $booking->save();

        DB::commit();

        return response()->json([
            'message' => 'Fixing process marked as done successfully',
            'fixingProgress' => $fixingProgress,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'An error occurred while marking the fixing process as done', 'details' => $e->getMessage()], 500);
    }
}



}