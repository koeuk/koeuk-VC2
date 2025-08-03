<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Progress;
use App\Models\Booking;
use App\Models\Bookin_immediately;
use App\Models\Bookin_deadline;
use App\Models\User;
use App\Models\Service;
use App\Models\FixingProgress;

class ProgressController extends Controller
{
    //
    public function index()
    {
        //
        $user = User::all();
        $Bookin_immediately = Bookin_immediately::all();
        $Bookin_deadline = Bookin_deadline::all();
        $service = Service::all();
        $bookings = Booking::all();
        $fixing_progress = FixingProgress::all();
        return view('progress.index',['bookings'=>$bookings,'users'=>$user,'deadlines'=>$Bookin_deadline,'immediatelys'=>$Bookin_immediately,'services'=>$service,'fixing_progress'=>$fixing_progress]);

    }

    public function destroy(int $id)
    {
        $fixingProgress = FixingProgress::find($id);
        $booking = Booking::find($fixingProgress->booking_id);
        $booking->action='request';
        $fixingProgress->delete();
        $booking->save();
        return redirect()->back()->with('showAlertDelete', true);
    }

}
