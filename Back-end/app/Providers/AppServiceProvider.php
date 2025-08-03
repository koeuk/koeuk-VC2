<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Mailsetting;
use Config;
use App\Models\Booking;
use App\Models\FixingProgress;
use App\Models\Chat;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Service;
use App\Models\User;
use App\Models\Payments;
use App\Models\Bookin_immediately;
use App\Models\Bookin_deadline;



use Schema;

class AppServiceProvider extends ServiceProvider
{
    /*
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /*
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('mailsettings')) {
            $mailsetting = Mailsetting::first();
            if($mailsetting){
                $data = [
                    'driver'            => $mailsetting->mail_transport,
                    'host'              => $mailsetting->mail_host,
                    'port'              => $mailsetting->mail_port,
                    'encryption'        => $mailsetting->mail_encryption,
                    'username'          => $mailsetting->mail_username,
                    'password'          => $mailsetting->mail_password,
                    'from'              => [
                        'address' => $mailsetting->mail_from,
                        'name'    => 'LaravelStarter'
                    ]
                ];
                Config::set('mail', $data);
            }
        }

            
        if (Schema::hasTable('bookings') && Schema::hasTable('fixing_progress') && Schema::hasTable('categories') && Schema::hasTable('services') && Schema::hasTable('users')&& Schema::hasTable('Chats')&& Schema::hasTable('feedback') && Schema::hasTable('fixing_progress') && Schema::hasTable('payments') && Schema::hasTable('bookin_immediatelies') && Schema::hasTable('bookin_deadlines')){
            view()->share(['bookings' => Booking::all(), 'FixingProgress' => FixingProgress::all(),'Categories'=>Category::all(),'Service'=> Service::all(),'users'=> User::all() ,'messages' => Chat::all(), 'feedbacks'=>Feedback::all(),'FixingProgress' => FixingProgress::all(),'payments' => Payments::all(),'bookin_immediatelies'=>Bookin_immediately::all(),'bookin_deadlines'=>Bookin_deadline::all()]);

        }

    }
}
