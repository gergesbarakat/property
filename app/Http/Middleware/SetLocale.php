<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // ✅ IMPORTANT: Add this import for logging

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Log that the middleware is running
        Log::info('SetLocale middleware is running.');

        if (Auth::check()) {
            $user = Auth::user();
            // Log the user's language setting
            Log::info('User is authenticated. Language is: ' . $user->lang);

            if ($user->lang) {
                // Set the application's language
                App::setLocale($user->lang);
                // Log that the locale was set
                Log::info('Locale set to: ' . App::getLocale());
            } else {
                Log::info('User has no language set. Using default.');
            }
        } else {
            Log::info('User is not authenticated. Using default locale.');
        }

        return $next($request);
    }
}
