<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // RateLimiter::for('api', function ($request) {
        //     return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        // });

        // Limit registrations to 5 per day per IP
        RateLimiter::for('register', function ($request) {
            return Limit::perDay(5)->by($request->ip())->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many registrations from this IP today',
                ], 429);
            });
        });

        // Limit comments to 10 per hour per student
        RateLimiter::for('comment', function ($request) {
            return Limit::perHour(10)->by(optional($request->user())->id)->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many comments from this user in the last hour',
                ], 429);
            });
        });

        // Limit course creation to 1 per day per teacher
        RateLimiter::for('course_create', function ($request) {
            return Limit::perDay(1)->by(optional($request->user())->id)->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many courses created by this teacher today',
                ], 429);
            });
        });

        // Limit content creation to 10 per hour per teacher
        RateLimiter::for('content_create', function ($request) {
            return Limit::perHour(10)->by(optional($request->user())->id)->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many content created by this teacher in the last hour',
                ], 429);
            });
        });
    }
}
