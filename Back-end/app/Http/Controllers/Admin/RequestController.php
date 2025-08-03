<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Bookin_immediately;
use App\Models\Bookin_deadline;
use App\Models\User;
use App\Models\Service;

class RequestController extends Controller
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
        return view('request.index',['bookings'=>$bookings,'users'=>$user,'deadlines'=>$Bookin_deadline,'immediatelys'=>$Bookin_immediately,'services'=>$service]);

    }

    // public function destroy(Request $request)
    // {
    //     $booking = Booking::find($id);
    //     if($booking['type'] == 'immediately'){
    //         $bookin_immediately = Bookin_immediately::find($booking->booking_type_id);
    //         $bookin_immediately->delete();
    //     }else{
    //         $bookin_deadline = Bookin_deadline::find($booking->booking_type_id);
    //         $bookin_deadline->delete();
    //     }
    //     $booking->delete();
    //     return redirect('admin/requests')->with('showAlertDelete', true);
    // }
    public function destroy(int $id)
    {
        $booking = Booking::find($id);
        if($booking['type'] == 'immediately'){
            $bookin_immediately = Bookin_immediately::find($booking->booking_type_id);
            $bookin_immediately->delete();
        }else{
            $bookin_deadline = Bookin_deadline::find($booking->booking_type_id);
            $bookin_deadline->delete();
        }
        $booking->delete();
        return redirect()->back()->with('showAlertDelete', true);
    }
}
