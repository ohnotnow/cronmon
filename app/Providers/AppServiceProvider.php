<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('emails', function ($attribute, $value, $parameters, $validator) {
            $value = preg_replace("/\s+/", "", $value);
            $mails = explode(',', $value);
            $rules = ['email' => 'email'];
            foreach ($mails as $mail) {
                $data = ['email' => $mail];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
