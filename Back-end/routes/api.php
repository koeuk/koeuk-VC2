<?php

use App\Http\Controllers\Admin\API\UserController;
// use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BookingController; 
use App\Http\Controllers\API\Bookin_memediatelyController;
use App\Http\Controllers\API\Bookin_deadlineController;
use App\Http\Controllers\Api\PromotionService;
use App\Http\Controllers\Api\FixingProgressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\FixerController;
use App\Http\Controllers\API\PromotionController;

use App\Http\Controllers\API\StripePaymentHandlerController;

 use App\Http\Controllers\API\MailController;
use App\Http\Controllers\API\NotificationController;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');
Route::put('/update/{id}', [AuthController::class, 'updateInformation'])->middleware('auth:sanctum');
Route::post('/update/profile/{id}', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');

Route::get('/fixer/list', [FixerController::class, 'index']);
Route::get('/post/list', [PostController::class, 'index'])->middleware('auth:sanctum');
Route::put('/profile/update/{id}', [AuthController::class, 'update'])->middleware('auth:sanctum');
Route::get('/fixer/list', [FixerController::class, 'index']);

Route::get('/service/list', [ServiceController::class, 'index']);
Route::get('/category/list', [CategoryController::class, 'index']);
Route::resource('/booking', BookingController::class)->middleware('auth:sanctum');


// ---------------- booking immedetely -------------
Route::post('/bookin_immediatly', [Bookin_memediatelyController::class, 'store']);
Route::get('/bookin_immediatly/{id}', [Bookin_memediatelyController::class, 'show'])->middleware('auth:sanctum');
Route::get('/bookin_immediatly', [Bookin_memediatelyController::class, 'index']) ;
Route::put('/bookin_immediatly/{id}', [Bookin_memediatelyController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/bookin_immediatly/{id}', [Bookin_memediatelyController::class, 'destroy'])->middleware('auth:sanctum');

// ---------------- booking deadline -------------
Route::get('/bookin_deadline', [Bookin_deadlineController::class, 'index'])->name('bookin_deadline.index');
// Route::post('/bookin_deadline/create', [Bookin_deadlineController::class, 'store'])->name('bookin_deadline.create');
Route::post('/bookin_deadline', [Bookin_deadlineController::class, 'store'])->name('bookin_deadline.store');
Route::get('/bookin_deadline/{bookin_deadline}', [Bookin_deadlineController::class, 'show'])->name('bookin_deadline.show');
Route::get('/bookin_deadline/{bookin_deadline}/edit', [Bookin_deadlineController::class, 'edit'])->name('bookin_deadline.edit');
Route::put('/bookin_deadline/{bookin_deadline}', [Bookin_deadlineController::class, 'update'])->name('bookin_deadline.update');
Route::delete('/bookin_deadline/{bookin_deadline}', [Bookin_deadlineController::class, 'destroy'])->name('bookin_deadline.destroy');



Route::resource('/fixing_progressing', FixingProgressController::class)->middleware('auth:sanctum');

Route::get('/service', [AuthController::class, 'index'])->name('service');
Route::get('/discount', [PromotionController::class, 'index'])->name('service');
Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion');
// Route::get('/promotion',[PromotionController::class, 'index'])->name('promotion');
Route::post('/promotion/create', [PromotionController::class, 'store'])->name('promotion.create');
Route::get('/promotion/show/{id}', [PromotionController::class, 'show'])->name('promotion.show');
Route::put('/promotion/update/{id}', [PromotionController::class, 'update'])->name('promotion.update');
Route::delete('/promotion/delete/{id}', [PromotionController::class, 'destroy'])->name('promotion.delete');

// --------------------------------- promotion API --------------------------------

Route::get('/promotion/list', [PromotionController::class, 'index']);


// --------------------------------- feedback API --------------------------------

Route::get('/feedback/list', [FeedbackController::class, 'index']);
Route::post('/feedback/create', [FeedbackController::class, 'store']);
Route::put('/feedback/update/{id}', [FeedbackController::class, 'update']);
Route::get('/feedback/show/{id}', [FeedbackController::class, "showByUser"]);
Route::delete('/feedback/delete/{id}', [FeedbackController::class, "destroy"]);

// --------------------- chat --------------------
Route::get('/booking', [BookingController::class, 'index']);
Route::delete('/customer/cancel/{id}', [BookingController::class, 'destroy']);
Route::get('/booking/show/{id}', [BookingController::class, 'show']);

// Chat routes
Route::get('/chat/list', [ChatController::class, 'index']);
Route::post('/chat/create', [ChatController::class, 'store']);
Route::get('/chat/show/{id}', [ChatController::class, "show"]);
Route::put('/chat/update/{id}', [ChatController::class, "update"]);
Route::delete('/chat/delete/{id}', [ChatController::class, "destroy"]);


// --------------- fixer in progress -------------//
Route::post('/fixer/accept', [FixingProgressController::class, 'store']);
Route::get('/fixer/accepted/{id}', [FixingProgressController::class, 'show']);
Route::delete('/fixer/cancel/{id}', [FixingProgressController::class, 'cancelAccept']);
Route::put('/fixer/start/{id}', [FixingProgressController::class, 'startFixer']);
Route::put('/fixer/done/{id}', [FixingProgressController::class, 'doneFixer']);
Route::get('/chats/{sender_id}/{receiver_id}', [ChatController::class, 'getChatsBySenderAndReceiver']);
Route::post('/fixer/register', [AuthController::class, 'fixerRegister']);



Route::post('/mail', [MailController::class, 'store']);
Route::post('/process-payment', 'PaymentController@processPayment');
// --------------------- admin routes -------------------------------
Route::post('/payment',[PaymentController::class,'store']);
Route::get('/getlistpay/{id}',[PaymentController::class,'getpayment']);
Route::get('/paid',[PaymentController::class,'getPay']);
Route::post('/payment/create',[PaymentController::class,'store']);
Route::post('/stripe/payment',[PaymentController::class,'makePayment']);

// Route::post('/stripe/payment', [PaymentController::class, 'makePayment']);
Route::get('/notification/show/{id}',[NotificationController::class,'show']);
Route::get('/notification/customer/{id}',[NotificationController::class,'customerNotification']);
Route::put('/notification/update/{id}',[NotificationController::class,'update']);
Route::delete('/notification/delete/{id}',[NotificationController::class,'destroy']);