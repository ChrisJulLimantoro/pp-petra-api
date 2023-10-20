<?php

namespace App\Providers;

use App\Models\Room;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('quota_within_capacity',function($attribute,$value,$parameters, $validator) {
            $room = Room::find($parameters[0]);

            return $room && $value <= $room->capacity;
        });
    }
}
