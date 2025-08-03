<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use Faker\Provider\ar_EG\Payment;
use App\Models\FixingProgress;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // ------- get payment in laravel ----------------------
    public function index()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $payments = Payments::all()->sortByDesc('id');
        return view('payments.index',['payments'=>$payments]);
    }
    // ------- get payment in laravel ----------------------

    // ------- get payment API ----------------------
    public function store(Request $request)
    {
        $fixdone = FixingProgress::all();
        $fixing = FixingProgress::where('action', 'done')
        ->get(['fixer_id']);
        $m1 = date('m', strtotime($request->datepay));
        $y1 = date('Y', strtotime($request->datepay));
        $fit =[];
        $numberfix = 0;
        foreach ($fixing as $fixer) {
            if(!in_array($fixer->fixer_id, $fit)){
                $fit[] = $fixer->fixer_id;
                $getfixer = $fixdone->where('fixer_id',$fixer->fixer_id);
                foreach ($getfixer as $fix){
                    $m2 = date('m', strtotime($fix->created_at));
                    $y2 = date('Y', strtotime($fix->created_at));
                    if ($m1 == $m2 && $y1 == $y2) {
                        $numberfix++;
                    }
                }
            }
            if($numberfix !=0){
                $payment = new Payments();
                $payment->fixer_id = $fixer->fixer_id;
                $payment->number_fixed = $numberfix;
                $payment->amount = $request->amount;
                $payment->total = $request->amount * $numberfix;
                $payment->datepay = $request->datepay;
                $payment->dateline = $request->dateline;
                $payment->description = $request->description;
                $payment->save();
                return redirect('admin/payments')
                ->with('showAlertCreate', true);            }
        }
        return redirect('admin/payments')
        ->with('showAlertNo', true);
    }


    // public function getPay(Request $request)
    // {
    //  // Set your secret key
    //  Stripe::setApiKey(env('STRIPE_SECRET'));

    //  // dd($request->amount);
    //  try {
    //      // Create a PaymentIntent to charge a customer
    //      $paymentIntent = PaymentIntent::create([
    //          'amount' => $request->amount, // Example amount in cents
    //          'currency' => 'usd',
    //          'payment_method_types' => ['card'],
    //          'description' => 'Example Payment',
    //      ]);

    //      // Return client secret to frontend
    //      return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    //  } catch (ApiErrorException $e) {
    //      // Handle error
    //      return response()->json(['error' => $e->getMessage()], 500);
    //  }
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
        return view('payments.new');
    }

 
    public function show(string $id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */

     public function edit(Payments $payment){
         return view('payments.edit', ['payment'=>$payment]);
     }
    public function update(Request $request, Payments $payment){
    
        //
        // $payment = Payments::find($id);
        // $payment->price = $request->price;
        // $payment->deadline = $request->deadline;
        // $payment->description = $request->description;
        // $payment->save();
        $payment->update($request->all());
        return redirect('admin/payments')->with('showAlertEdit', true);
    }

    public function makePayment(Request $request)
    {
        // Set your secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // dd($request->amount);
        try {
            // Create a PaymentIntent to charge a customer
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount, // Example amount in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => 'Example Payment',
            ]);

            // Return client secret to frontend
            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (ApiErrorException $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
