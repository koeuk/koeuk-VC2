<?php

use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ProfileController,
    MailSettingController,
    UserController,
    ChatController,
};
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
    // return redirect()->route('login');
});

Route::get('/fixerForm', function () {
    return view('fixerForm');
    // return redirect()->route('login');
});


Route::get('/test-mail',function(){

    $message = "Testing mail";

    Mail::raw('Hi, welcome!', function ($message) {
      $message->to('ajayydavex@gmail.com')
        ->subject('Testing mail');
    });

    dd('sent');

});


Route::get('/dashboard', function () {
    return view('front.dashboard');
})->middleware(['front'])->name('dashboard');


require __DIR__.'/front_auth.php';

// Admin routes
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('admin.dashboard');


require __DIR__.'/auth.php';




Route::namespace('App\Http\Controllers\Admin')->name('admin.')->prefix('admin')
    ->group(function(){
        Route::resource('roles','RoleController');
        Route::resource('permissions','PermissionController');
        Route::resource('users','UserController');
        Route::resource('posts','PostController');
        Route::resource('services','ServiceController');
        Route::resource('categories','CategoryController');
        Route::resource('discounts','DiscountController');
        Route::resource('requests','RequestController');
        Route::resource('progresss','ProgressController');
        Route::resource('dones','DoneController');
        Route::resource('feedbacks','FeedbackController');
        Route::resource('chats','ChatController');
        Route::resource('payments','PaymentController');


        Route::get('/profile',[ProfileController::class,'index'])->name('profile');
        Route::put('/update/{id}', [UserController::class, 'updateInformation']);
        Route::post('/update/profile/{id}',[UserController::class,'updateProfile'])->name('profile.update');
        Route::get('/mail',[MailSettingController::class,'index'])->name('mail.index');
        Route::put('/mail-update/{mailsetting}',[MailSettingController::class,'update'])->name('mail.update');
        Route::post('/admin/chat/store', [ChatController::class, 'store'])->name('admin.ChatController.store');

});

// routes/web.php
// Route::post('/process-payment', 'PaymentController@processPayment');

